<?php

/**
 * This file is part of Bonfire.
 *
 * (c) Lonnie Ezell <lonnieje@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bonfire\Widgets\Types\Charts;

/**
 * Represents a collection of Charts items.
 *
 * @property string $name
 * @property string $title
 */
class ChartsCollection extends ChartsItem
{
    protected array $items = [];

    /**
     * The name this collection is discovered by.
     *
     * @var string
     */
    protected $name;

    public function setName(string $name): ChartsCollection
    {
        $this->name = $name;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Adds a single item to the menu.
     */
    public function addItem(ChartsItem $item): ChartsCollection
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Add multiple items at once.
     */
    public function addItems(array $items): ChartsCollection
    {
        $this->items = array_merge($this->items, $items);

        return $this;
    }

    public function removeItem(string $title)
    {
        $counter = count($this->items);

        for ($i = 0; $i < $counter; $i++) {
            if ($this->items[$i]->title() === $title) {
                unset($this->items[$i]);
                break;
            }
        }
    }

    /**
     * Removes all of the items from this collection.
     */
    public function removeAllItems(): ChartsCollection
    {
        $this->items = [];

        return $this;
    }

    /**
     * Returns all items in the Collection,
     * sorted by weight, where larger weights
     * make them fall to the bottom.
     */
    public function items(): array
    {
        $this->sortItems();

        return $this->items;
    }

    /**
     * Sorts the items by the weight,
     * ensuring that bigger weights
     * drop to the bottom.
     */
    protected function sortItems()
    {
        usort($this->items, static function ($a, $b) {
            if ($a->title === $b->title) {
                return $a->title <=> $b->title;
            }

            return $a->weight <=> $b->weight;
        });
    }

    public function __get(string $key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }
    }
}
