<?php
namespace VMware\VCloud;

/**
 * Utility class that provides generic getters and setters
 */
abstract class AbstractObject
{
    /**
     * Generic getter. If the value of $this->myValue is null, it calls the
     * creator method (either $this->createMyValue, or the
     * given creator method name).
     *
     * Setting $createMethod to false disables the call to the creator method.
     *
     * @param string $name         The field name
     * @param string $createMethod The creator method name, defaults to 'createMyValue' if name is 'myValue'
     * @throws Exception\UnknownClassField If the field does not exist in the class
     * @return mixed Returns the value of the field $name
     */
    protected function get($name, $createMethod = null)
    {
        if (!isset($this->$name)) {
            throw new Exception\UnknownClassField(get_class($this), $name);
        }

        if ($this->$name === null && $createMethod !== false) {
            if ($createMethod === null) {
                $createMethod = 'create' . ucfirst($name);
            }
            $this->$name = $this->$createMethod();
        }

        return $this->$name;
    }

    /**
     * Generic setter.
     *
     * @throws Exception\UnknownClassField If the field does not exist in the class
     * @return AbstractObject Returns this object to allow chaining
     */
    protected function set($name, $value)
    {
        if (!isset($this->$name)) {
            throw new Exception\UnknownClassField(get_class($this), $name);
        }

        $this->$name = $value;
        return $this;
    }

    /**
     * Add an element to an array
     *
     * @param string $name  The name of the array field
     * @param string $value The value to set
     * @throws Exception\UnknownClassField  If the field does not exist in the class
     * @throws Exception\ClassFieldNotArray If the field is not an array
     * @return AbstractObject Returns this object to allow chaining
     */
    protected function add($name, $value)
    {
        if (!isset($this->$name)) {
            throw new Exception\UnknownClassField(get_class($this), $name);
        }
        if (!is_array($this->$name)) {
            throw new Exception\ClassFieldNotArray(get_class($this), $name);
        }

        $this->$name[] = $value;
        return $this;
    }

    /**
     * Remove an element from an array, by its index
     *
     * @param string $name  The name of the array field
     * @param string $index The index of the value to remove
     * @throws Exception\UnknownClassField  If the field does not exist in the class
     * @throws Exception\ClassFieldNotArray If the field is not an array
     * @throws Exception\IndexOutOfBounds   If the index is outside the array range
     * @return AbstractObject Returns this object to allow chaining
     */
    protected function removeByIndex($name, $index)
    {
        if (!isset($this->$name)) {
            throw new Exception\UnknownClassField(get_class($this), $name);
        }
        if (!is_array($this->$name)) {
            throw new Exception\ClassFieldNotArray(get_class($this), $name);
        }
        if ($index < 0 || $index >= count($this->$name)) {
            throw new Exception\ClassFieldNotArray($name, $index);
        }

        unset($this->$name[$index]);
        return $this;
    }

    /**
     * Remove the first element of an array with the specified value.
     *
     * @param string $name  The name of the array field
     * @param string $value The value to remove
     * @throws Exception\UnknownClassField  If the field does not exist in the class
     * @throws Exception\ClassFieldNotArray If the field is not an array
     * @throws Exception\IndexOutOfBounds   If the index is outside the array range
     * @return AbstractObject Returns this object to allow chaining
     */
    protected function remove($name, $value)
    {
        if (!isset($this->$name)) {
            throw new Exception\UnknownClassField(get_class($this), $name);
        }
        if (!is_array($this->$name)) {
            throw new Exception\ClassFieldNotArray(get_class($this), $name);
        }

        if (($index = array_search($value, $this->$name)) === false) {
            throw new Exception\IndexOutOfRange($this->$name, $index);
        }

        unset($this->$name[$index]);
        return $this;
    }
}
