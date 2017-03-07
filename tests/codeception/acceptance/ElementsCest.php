<?php

class ElementsCest
{
    private $faker;
    private $title;
    private $tags;
    private $urld;

    public function _before(AcceptanceTester $I)
    {
        $this->faker = Faker\Factory::create();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    private function loginOwner(AcceptanceTester $I)
    {
        $I->amOnPage('/prozorrosale/user/logout');
        $I->amOnPage('/');
        $I->click('Увійти');
        $I->fillField('input[name="LoginForm[username]"]', 'testadmin@test.t');
        $I->fillField('input[name="LoginForm[password]"]', 'testpass');
        $I->click('login-button');
        $I->dontSee('.help-block-error');
    }

    private function loginAdmin(AcceptanceTester $I)
    {
        $I->amOnPage('/prozorrosale/user/logout');
        $I->amOnPage('/');
        $I->click('Увійти');
        $I->fillField('input[name="LoginForm[username]"]', 'admin@admin.a');
        $I->fillField('input[name="LoginForm[password]"]', 'adminpass');
        $I->click('login-button');
        $I->dontSee('.help-block-error');
    }

    //guest user admin page
    public function tryAdminPageGuest(AcceptanceTester $I)
    {
        $I->amOnPage('/prozorrosale/user/logout');
        $I->amOnPage('/pages/elements');
        $I->see('Вхід');
    }

    //no admin user admin page
    public function tryAdminPageNoRights(AcceptanceTester $I)
    {
        $I->amOnPage('/prozorrosale/user/logout');
        $this->loginOwner($I);
        $I->amOnPage('/pages/elements');
        $I->see('403');
    }
    //admin page
    public function tryAdminPage(AcceptanceTester $I)
    {
        $this->loginAdmin($I);
        $I->amOnPage('/pages/elements');
        $I->see('Manage content elements');
    }
    //C
    public function tryCreateElement(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->click('Create Element');

        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }

        $this->title = $this->faker->text(20);
        $this->urld = $this->faker->word().'-'.$this->faker->word();

        $I->fillField('#elements-title',$this->title);
        $I->fillField('#elements-urld',$this->urld);
        $I->fillField('#elements-preview',$this->faker->text(40));

        $I->fillField('.redactor-editor',$this->faker->text(160));

        $this->tags = $this->faker->word().'_'.$this->faker->word();

        $I->fillField('#elements-tags',$this->tags);
        $I->fillField('#elements-meta_title',$this->faker->text(30));
        $I->fillField('#elements-meta_descr',$this->faker->text(70));
        $I->fillField('#elements-meta_keys','test, testtest, testtesttest');

        $I->selectOption('#elements-type',1);
        $I->selectOption('#elements-status',2);
        $I->selectOption('#elements-parent',rand(29,30));

        $I->click('#element_upload_file');
        $I->attachFile('#element_upload_file','testfile.jpg');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }

        $I->see('testfile');

        $I->click('#create_element_submit');

        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }

        $I->see('Element successfully created');
    }
    //R
        //admin one element
    public function tryAdminFindAndRead(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->fillField('ElementsSearch[title]',$this->title);
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->see($this->tags);
    }
        //frontend static page
    public function tryPublicPageRead(AcceptanceTester $I)
    {
        $I->amOnPage('/pages');
        $I->fillField('#elementssearch-all',$this->title);
        $I->click('Search');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->see($this->title);
        $I->click('More');
        $I->see($this->title);
    }
    //U
    public function tryAdminFindAndUpdate(AcceptanceTester $I)
    {
        $this->tryAdminFindAndRead($I);
        $I->click('.glyphicon-pencil');

        $this->title = $this->faker->text(40);
        $I->fillField('#elements-title',$this->title);

        $I->click('Save');

        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }

        $I->see('Element successfully update');

        $this->tryAdminFindAndRead($I);
    }
    //D
    /*public function tryAdminFindAndDelete(AcceptanceTester $I)
    {
        $this->tryAdminFindAndRead($I);
        $I->click('.glyphicon-trash');
        $I->acceptPopup();

        $I->dontSee($this->title);
    }*/
}
