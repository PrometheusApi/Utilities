<?php
namespace PrometheusApi\Utilities\Uri;

class Parser implements \PrometheusApi\Utilities\Contracts\Uri\Parser
{

    /**
     * @inheritdoc
     */
    public function entities($uri, $idPlaceholder = null)
    {
        if ($idPlaceholder === null) {
            $callback = function ($value) {
                if (!is_numeric($value) && $this->itIsAnArrayOfIds($value) === false && $this->notAQuery($value)) {
                    return $value;
                }
            };
        } else {
            $callback = function ($value) use ($idPlaceholder) {
                if ($value !== $idPlaceholder && false === $this->itIsAnArrayOfPlaceholders($value, $idPlaceholder) && $this->notAQuery($value)) {
                    return $value;
                }
            };
        }

        return array_values(array_filter(array_map($callback, $this->explodeUri($uri))));
    }

    /**
     * @inheritdoc
     */
    public function getQuery($uri)
    {
        $result = explode('?', $uri);

        if (!array_key_exists(1, $result)) {
            return null;
        }

        return $result[1];
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getResource($uri)
    {
        return explode('?', $uri)[0];
    }

    /**
     * @inheritdoc
     */
    public function idEntities($uri, $idPlaceholder = null)
    {
        $entities = $this->entities($uri, $idPlaceholder);
        $idsNeeded = $this->ids($uri, $idPlaceholder);

        return array_slice($entities, 0, count($idsNeeded));
    }

    /**
     * @inheritdoc
     */
    public function ids($uri, $idPlaceholder = null)
    {
        if ($idPlaceholder) {

            $callback = function ($value) use ($idPlaceholder) {
                if ($this->placeholderItIs($value, $idPlaceholder)) {
                    return $value;
                }
                if ($this->itIsAnArrayOfPlaceholders($value, $idPlaceholder)) {
                    return $value;
                }

                return null;
            };

        } else {
            $callback = function ($value) {

                if ($this->itIsAnId($value)) {
                    return $this->itIsAnId($value);
                }

                if ($this->itIsAnArrayOfIds($value)) {
                    return $this->itIsAnArrayOfIds($value);
                }

                return null;
            };
        }

        return array_values(array_filter(array_map($callback, $this->explodeUri($uri))));
    }

    /**
     * @inheritdoc
     */
    public function idsNeededCount($uri, $placeholder)
    {
        $ids = $this->ids($uri, $placeholder);

        $idsNeededCount = [];

        foreach ($ids as $idString) {
            $idsNeededCount[] = substr_count($idString, $placeholder);
        }

        return $idsNeededCount;
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    protected function explodeUri($uri)
    {
        return explode('/', $uri);
    }

    /**
     * @param string $string
     *
     * @return array|bool
     */
    protected function itIsAnArrayOfIds($string)
    {
        $explode = explode(',', $string);
        if (count(array_filter($explode, 'is_numeric')) > 1) {
            return array_map('intval', $explode);
        }

        return false;
    }

    /**
     * @param string $string
     *
     * @return int
     */
    protected function itIsAnId($string)
    {
        if (is_numeric($string)) {
            return (int)$string;
        }
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    protected function notAQuery($string)
    {
        return strpos($string, '?') === false;
    }

    protected function placeholderItIs($value, $placeholder)
    {
        return $value === $placeholder;
    }

    private function itIsAnArrayOfPlaceholders($value, $idPlaceholder)
    {
        $array = explode(',', $value);

        $count = count($array);

        $filteredArray = array_filter($array, function ($value) use ($idPlaceholder) {
            if ($value === $idPlaceholder) {
                return $value;
            }
        });

        return count($filteredArray) === $count;
    }
}
