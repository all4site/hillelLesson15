<?php


namespace App\Event;


use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductInsertEvent extends Event
{
	public const NAME = 'product.insert';

	/**
	 * @var Product
	 */
	private $product;

	public function __construct(Product $product)
	{
		$this->product = $product;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}
}