<?php
namespace ExtendedPack\Bundle\Model;

class AccessoriesListItem
{
	protected $imageUrl;
	protected $title;
	protected $description;
	protected $qty;

    public function getImageUrl(): ?string
	{
		return $this->imageUrl;
	}

	public function setImageUrl($imageUrl): self
	{
		$this->imageUrl = $imageUrl;
		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription($description): self
	{
		$this->description = $description;
		return $this;
	}

	public function getQty()
	{
		return $this->qty;
	}

	public function setQty($qty): self
	{
		$this->qty = $qty;
		return $this;
	}

}