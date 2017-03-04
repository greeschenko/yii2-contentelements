<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class DealsPublic extends BasePage
{
    public $route = 'deals/publiclist';

    /**
     * @param string $username
     * @param string $password
     */
    public function search($attr)
    {
        $this->actor->fillField('input[name="DealsSearch[name]"]', $attr['name']);
        $this->actor->fillField('input[name="DealsSearch[number]"]', $attr['number']);
        $this->actor->fillField('input[name="DealsSearch[stat_end_time_from]"]', $attr['stat_end_time_from']);
        $this->actor->fillField('input[name="DealsSearch[stat_end_time_to]"]', $attr['stat_end_time_to']);
        $this->actor->fillField('input[name="DealsSearch[price_from]"]', $attr['price_from']);
        $this->actor->fillField('input[name="DealsSearch[price_to]"]', $attr['price_to']);

        $this->actor->click('search-btn');
    }
}
