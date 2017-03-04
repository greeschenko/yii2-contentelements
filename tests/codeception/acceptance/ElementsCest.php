<?php

date_default_timezone_set('Europe/Kiev');

class ElementsCest
{
    private $faker;

    public function _before(AcceptanceTester $I)
    {
        $this->faker = Faker\Factory::create();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    private function loginOwner(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Увійти');
        $I->fillField('input[name="LoginForm[username]"]', 'admin@admin.a');
        $I->fillField('input[name="LoginForm[password]"]', 'adminpass');
        $I->click('login-button');
        $I->dontSee('.help-block-error');
    }

    //no admin user admin page
    public function tryAdminPageNoRights(AcceptanceTester $I)
    {
        $I->amOnPage('/pages/admin');
        $I->see('403');
    }
    //admin page
    public function tryAdminPage(AcceptanceTester $I)
    {
        $this->loginOwner($I);
        $I->amOnPage('/pages/admin');
        $I->see('Manage content elements');
    }
    //C
    public function tryCreateElement(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->click('Create Element');

        $I->fillField('#elements-title',$this->faker->text(20));
        $I->fillField('#elements-urld',$this->text);
        $I->fillField('#elements-user_id',$this->text);
        $I->fillField('#elements-parent',$this->text);
        $I->fillField('#elements-created_at',$this->text);
        $I->fillField('#elements-updated_at',$this->text);
        $I->fillField('#elements-preview',$this->text);
        $I->fillField('#elements-content',$this->text);
        $I->fillField('#elements-tags',$this->text);
        $I->fillField('#elements-meta_title',$this->text);
        $I->fillField('#elements-meta_descr',$this->text);
        $I->fillField('#elements-meta_keys',$this->text);

        $I->selectOption('#elements-type',1);
        $I->selectOption('#elements-status',2);

        $I->fillField('#elements-atachments',$this->text);

        $I->click('Create');
    }
    //R
        //admin one element
    public function tryAdminFindAndRead(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->click('Create Element');
    }
        //frontend static page
    public function tryPublicPage(AcceptanceTester $I)
    {
        $I->amOnPage('/pages');
        $I->see('pages public page');
    }
        //frontend dinamic page
        //frontend dinamic page search
        //frontend dinamic page element
    //U
    public function tryAdminFindAndUpdate(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->click('Create Element');
    }
    //D
    public function tryAdminFindAndDelete(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->click('Create Element');
    }
}
