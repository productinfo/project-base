<?php

namespace SS6\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\Persistence\ObjectManager;
use SS6\ShopBundle\Component\DataFixture\AbstractReferenceFixture;
use SS6\ShopBundle\Model\Slider\SliderItem;
use SS6\ShopBundle\Model\Slider\SliderItemData;

class SliderItemDataFixture extends AbstractReferenceFixture{

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 */
	public function load(ObjectManager $manager) {
		$sliderItemData = new SliderItemData();

		$sliderItemData->name = 'Shopsys';
		$sliderItemData->link = 'http://www.shopsys.cz/';

		$this->createSliderItem($manager, $sliderItemData, 1);

		$sliderItemData->name = 'Twitter';
		$sliderItemData->link = 'https://twitter.com/netdevelo_cz';

		$this->createSliderItem($manager, $sliderItemData, 1);

		$sliderItemData->name = 'Pojďte s námi růst';
		$sliderItemData->link = 'http://www.pojdtesnamirust.cz/';

		$this->createSliderItem($manager, $sliderItemData, 1);

		$manager->flush();
	}

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 * @param \SS6\ShopBundle\Model\Slider\SliderItemData $sliderItemData
	 * @param int $domainId
	 */
	private function createSliderItem(
		ObjectManager $manager,
		SliderItemData $sliderItemData,
		$domainId
	) {
		$sliderItem = new SliderItem($sliderItemData, $domainId);
		$manager->persist($sliderItem);
	}
}
