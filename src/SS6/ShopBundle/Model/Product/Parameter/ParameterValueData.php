<?php

namespace SS6\ShopBundle\Model\Product\Parameter;

class ParameterValueData {

	/**
	 * @var string|null
	 */
	public $text;

	/**
	 * @param string|null $text
	 */
	public function __construct($text = null) {
		$this->text = $text;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Parameter\ParameterValue $parameterValue
	 */
	public function setFromEntity(ParameterValue $parameterValue) {
		$this->text = $parameterValue->getText();
	}

}
