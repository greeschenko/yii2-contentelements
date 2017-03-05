<?php

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
        $I->fillField('input[name="LoginForm[username]"]', 'testadmin@test.t');
        $I->fillField('input[name="LoginForm[password]"]', 'testpass');
        $I->click('login-button');
        $I->dontSee('.help-block-error');
    }

    private function loginAdmin(AcceptanceTester $I)
    {
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

        $I->fillField('#elements-title',$this->faker->text(20));
        $I->fillField('#elements-urld',$this->faker->text(20));
        $I->fillField('#elements-preview',$this->faker->text(40));

        $I->fillField('.redactor-editor',$this->faker->text(160));

        $I->fillField('#elements-tags','test, testtest, testtesttest');
        $I->fillField('#elements-meta_title',$this->faker->text(30));
        $I->fillField('#elements-meta_descr',$this->faker->text(70));
        $I->fillField('#elements-meta_keys','test, testtest, testtesttest');

        $I->selectOption('#elements-type',2);
        $I->selectOption('#elements-status',2);
        $I->selectOption('#elements-parent',rand(3,4));

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
        $I->fillField('#admin-elements-search-all','testtitle1111');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->click('.admin_view_bnt');
        $I->see('testtitle1111');
    }
        //frontend static page
    public function tryPublicPageRead(AcceptanceTester $I)
    {
        $I->amOnPage('/pages');
        $I->fillField('#elements-search-all','testtitle1111');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->click('.view_bnt');
        $I->see('testtitle1111');
    }
    //U
    public function tryAdminFindAndUpdate(AcceptanceTester $I)
    {
        $contr_string = 'new unic text for element content 49303';
        $this->tryAdminPage($I);
        $I->fillField('#admin-elements-search-all','testtitle1111');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->click('.admin_edit_bnt');

        $I->fillField('#elements-content',$contr_string);

        $I->click('Save');

        $I->see('Element successfully update');
    }
    //D
    public function tryAdminFindAndDelete(AcceptanceTester $I)
    {
        $this->tryAdminPage($I);
        $I->fillField('#admin-elements-search-all','testtitle1111');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->click('.admin_delete_bnt');
        $I->click('.admin_delete_confirm_bnt');

        $I->dontSee('testtitle1111');
    }
}
