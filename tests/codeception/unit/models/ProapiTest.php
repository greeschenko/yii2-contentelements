<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use Codeception\Specify;
use app\proapi\Driver;
use app\models\Options;
use app\models\Proelements;

class ProapiTest extends TestCase
{
    protected $api;
    protected $data;

    use Specify;

    public function setUp()
    {
        parent::setUp();
        date_default_timezone_set('Europe/Kiev');
        $this->api = new Driver(
            \Yii::$app->params['prourl'],
            \Yii::$app->params['proname'],
            \Yii::$app->params['prokey']
        );

        $this->data = [
            "data"=>[
                "title"=>"продаж тестового лоту",
                "minimalStep"=>[
                    "currency"=>"UAH",
                    "amount"=>100.0,
                    "valueAddedTaxIncluded"=>true
                ],
                "auctionPeriod"=>[
                    "startDate"=> date('Y-m-d\TH:i:s.u+03:00',time()+(60*7)),
                    "endDate"=> date('Y-m-d\TH:i:s.u+03:00',time()+(60*10)),
                ],
                "procurementMethodType"=> "dgfOtherAssets",
                "value"=>[
                    "currency"=>"UAH",
                    "amount"=>500.0,
                    "valueAddedTaxIncluded"=>true
                ],
                "procuringEntity"=>[
                    "contactPoint"=>[
                        "name"=>"Державне управління справами",
                        "telephone"=>"0440000000"
                    ],
                    "identifier"=>[
                        "scheme"=>"UA-EDR",
                        "id"=>"00037256",
                        "uri"=>"http://www.dus.gov.ua/"
                    ],
                    "name"=>"Державне управління справами",
                    "kind"=> "general",
                    "address"=>[
                        "countryName"=>"Україна",
                        "postalCode"=>"01220",
                        "region"=>"м. Київ",
                        "streetAddress"=>"вул. Банкова, 11, корпус 1",
                        "locality"=>"м. Київ"
                    ]
                ],
                "items"=>[
                    [
                        "classification"=>[
                            "scheme"=>"CAV",
                            "id"=>"06000000-2",
                            "description"=>"Земельні ділянки",
                        ],
                        "description"=>"Земля для військовослужбовців",
                        "unit"=>[
                            "code"=>"44617100-9",
                            "name"=>"item"
                        ],
                        "quantity"=>7
                    ]
                ],
                "submissionMethodDetails"=> "quick",
                "procurementMethodDetails"=> "quick, accelerator=1440",
                "mode"=> "test",
            ]
        ];
    }

    /**
     * getElement
     *
     * @param mixed $status
         active.enquiries,
         active.tendering,
         active.auction,
         active.qualification,
         active.awarded,
         unsuccessful,
         complete,
         cancelled,
     * @access protected
     * @return void
     */
    protected function getElement($status)
    {
        $res = [];
        $data = Proelements::find()
            ->where(['el_type' => Proelements::TYPE_TENDER])
            ->all();

        foreach ($data as $one) {
            $req = $this->api->getAuction($id);
            if ($req->data->status == $status) {
                $res['id'] = $one->el_id;
                $res['token'] = $one->el_token;
                break;
            }
        }

        $res = $res + $this->getSubData($data,$res['id']);

        return $res;
    }

    protected function getLastElement()
    {
        $res = [];
        $data = Proelements::find()
            ->where(['el_type' => Proelements::TYPE_TENDER])
            ->orderBy('id DESC')
            ->one();

        if ($data != null) {
            $res['id'] = $data->el_id;
            $res['token'] = $data->el_token;
        }

        $res = $res + $this->getSubData($data,$res['id']);

        return $res;
    }

    protected function getSubData($data, $parent)
    {
        $res = [];
        foreach ($data->eltypelist as $key => $value) {
            $subdata = Proelements::find()
                ->where(['parent' => $parent])
                ->andWhere(['el_type' => $key])
                ->all();

            if (count($subdata) > 0) {
                foreach ($subdata as $one) {
                    $res[$key][] = [
                        'id' => $one->el_id,
                        'token' => $one->el_token,
                        'sysid' => $one->id,
                    ] + $this->getSubData($data,$one->el_id);
                }
            }
        }

        return $res;
    }

    //важно
    //Створення закупівлі [8]
    //Модифікація закупівлі [8]
    //Завантаження документації [3]
    //Уточнення (запитання покупця та відповіді) [2]
    //Реєстрація пропозиції [3]

    public function testAuctionCreate()
    {
        $req = $this->api->createAuction($this->data);

        Proelements::push(
            Proelements::TYPE_TENDER,
            $req->data->id,
            $req->access->token
        );

        $this->specify('check is auction create', function () use ($req) {
            expect('isset token', isset($req->access->token))->true();
        });
    }

    public function testAuctionUpdate()
    {
        $n = rand(1,10);
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];
        $data = [
            "data"=>[
                'description' => 'test description',
            ]
        ];

        $req = $this->api->updateAuction($id,$data,$token);

        $this->specify('check is auction update', function () use ($req,$n) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testAuctionAddFile()
    {
        $file = '/var/www/site/tests/codeception/_data/testfile.jpg';

        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];

        $req = $this->api->addFile($id,$file,$token);

        Proelements::push(
            Proelements::TYPE_DOC,
            $req->data->id,
            false,
            $id
        );

        $this->specify('check is file uploded', function () use ($req) {
            expect('isset file url', isset($req->data->url))->true();
        });
    }

    public function testAuctionUpdateFileInfo()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];
        $file_id = $last[Proelements::TYPE_DOC][0]['id'];

        $data = [
            "data"=>[
                "description"=>"document description modified"
            ]
        ];

        $req = $this->api->updateFileInfo($id,$file_id,$data,$token);

        $this->specify('check is info updated', function () use ($req) {
            expect('isset file description', isset($req->data->description))->true();
        });
    }

    public function testAuctionReplaceFile()
    {
        $file = '/var/www/site/tests/codeception/_data/testfile2.jpg';
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];
        $file_id = $last[Proelements::TYPE_DOC][0]['id'];

        $req = $this->api->replaceFile($id,$file_id,$file,$token);

        $this->specify('check is file replace', function () use ($req) {
            expect('isset file id', isset($req->data->id))->true();
        });
    }

    public function testAuctionAddQuestion()
    {
        sleep(2);
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];

        $data = [
            "data"=>[
                "author"=>[
                    "contactPoint"=>[
                        "telephone"=>"+380 (432) 21-69-30",
                        "name"=>"Сергій Олексюк",
                        "email"=>"soleksuk@gmail.com"
                    ],
                    "identifier"=>[
                        "scheme"=>"UA-EDR",
                        "legalName"=>"Державне комунальне підприємство громадського харчування «Школяр»",
                        "id"=>"00137226",
                        "uri"=>"http://sch10.edu.vn.ua/"
                    ],
                    "name"=>"ДКП «Школяр»",
                    "address"=>[
                        "countryName"=>"Україна",
                        "postalCode"=>"21100",
                        "region"=>"м. Вінниця",
                        "streetAddress"=>"вул. Островського, 33",
                        "locality"=>"м. Вінниця"
                    ]
                ],
                "description"=>"Просимо додати таблицю потрібної калорійності харчування",
                "title"=>"Калорійність"
            ]
        ];

        $req = $this->api->addQuestion($id,$data);

        Proelements::push(
            Proelements::TYPE_QUESTION,
            $req->data->id,
            false,
            $id
        );

        $this->specify('check is question added', function () use ($req) {
            expect('isset question id', isset($req->data->id))->true();
        });
    }

    public function testAuctionAddAnswer()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];
        $qid = $last[Proelements::TYPE_QUESTION][0]['id'];

        $req = $this->api->addAnswer($id,$qid,$token,'test answer');

        $this->specify('check is answer added', function () use ($req) {
            expect('isset answer id', isset($req->data->answer))->true();
        });
    }

    public function testAddBid()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last['token'];

        $data = [
            "data"=>[
                "status"=>"active", //['registration', 'validBid', 'invalidBid']
                "qualified"=> true,
                "value"=>[
                    "amount"=>5000000000
                ],
                "tenderers"=>[
                    [
                        "contactPoint"=>[
                            "telephone"=>"+380 (432) 21-69-30",
                            "name"=>"Грищенко Олексій",
                            "email"=>"polonex@gmail.com"
                        ],
                        "identifier"=>[
                            "scheme"=>"UA-EDR",
                            "id"=>"2452342343",
                            "uri"=>"http://www.polonex.in.ua/"
                        ],
                        "name"=>"Polonex",
                        "address"=>[
                            "countryName"=>"Україна",
                            "postalCode"=>"01601",
                            "region"=>"м. Київ",
                            "streetAddress"=>"вул. Бульварно-Кудрявська, 33-Б, 3 поверх, оф.4",
                            "locality"=>"м. Київ"
                        ]
                    ]
                ]
            ]
        ];

        $req = $this->api->addBid($id,$data);

        Proelements::push(
            Proelements::TYPE_BID,
            $req->data->id,
            $req->access->token,
            $id
        );

        $this->specify('check is bid added', function () use ($req) {
            expect('isset token', isset($req->access->token))->true();
        });
    }

    public function testBidUpdate()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $token = $last[Proelements::TYPE_BID][0]['token'];
        $bid_id = $last[Proelements::TYPE_BID][0]['id'];

        $data = [
            "data"=>[
                "value"=>[
                    "amount"=>rand(6300000000,6500000000)
                ],
            ]
        ];

        $req = $this->api->updateBid($id,$bid_id,$data,$token);

        $this->specify('check is bid update', function () use ($req) {
            expect('isset modified field', isset($req->data->value))->true();
        });
    }

    public function testBidAddFile()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $bid_id = $last[Proelements::TYPE_BID][0]['id'];
        $token = $last[Proelements::TYPE_BID][0]['token'];
        $file = '/var/www/site/tests/codeception/_data/testfile.jpg';

        $req = $this->api->addFileToBid($id,$bid_id,$file,$token);

        Proelements::push(
            Proelements::TYPE_DOC,
            $req->data->id,
            false,
            $bid_id
        );

        $this->specify('check is file uploded', function () use ($req) {
            expect('isset file url', isset($req->data->url))->true();
        });
    }

    public function testBidUpdateFileInfo()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $bid_id = $last[Proelements::TYPE_BID][0]['id'];
        $token = $last[Proelements::TYPE_BID][0]['token'];
        $file_id = $last[Proelements::TYPE_BID][0][Proelements::TYPE_DOC][0]['id'];

        $data = [
            "data"=>[
                "description"=>"document description modified"
            ]
        ];

        $req = $this->api->updateBidFileInfo($id,$bid_id,$file_id,$data,$token);

        $this->specify('check is info updated', function () use ($req) {
            expect('isset file description', isset($req->data->description))->true();
        });
    }

    public function testBidReplaceFile()
    {
        $file = '/var/www/site/tests/codeception/_data/testfile.jpg';
        $last = $this->getLastElement();
        $id = $last['id'];
        $bid_id = $last[Proelements::TYPE_BID][0]['id'];
        $token = $last[Proelements::TYPE_BID][0]['token'];
        $file_id = $last[Proelements::TYPE_BID][0][Proelements::TYPE_DOC][0]['id'];

        $req = $this->api->replaceBidFile($id,$bid_id,$file_id,$file,$token);

        $this->specify('check is file replace', function () use ($req) {
            expect('isset file id', isset($req->data->id))->true();
        });
    }

    public function testAddSecondBid()
    {
        $last = $this->getLastElement();
        $id = $last['id'];

        $data = [
            "data"=>[
                "status"=>"active", //['registration', 'validBid', 'invalidBid']
                "qualified"=> true,
                "value"=>[
                    "amount"=>4000000000
                ],
                "tenderers"=>[
                    [
                        "contactPoint"=>[
                            "telephone"=>"+380 (432) 21-69-30",
                            "name"=>"Тест Тестович Тестенко",
                            "email"=>"polonex@gmail.com"
                        ],
                        "identifier"=>[
                            "scheme"=>"UA-EDR",
                            "id"=>"2452342343",
                            "uri"=>"http://www.polonex.in.ua/"
                        ],
                        "name"=>"Polonex",
                        "address"=>[
                            "countryName"=>"Україна",
                            "postalCode"=>"01601",
                            "region"=>"м. Київ",
                            "streetAddress"=>"вул. Бульварно-Кудрявська, 33-Б, 3 поверх, оф.4",
                            "locality"=>"м. Київ"
                        ]
                    ]
                ]
            ]
        ];

        $req = $this->api->addBid($id,$data);

        $this->specify('check is bid added', function () use ($req) {
            expect('isset token', isset($req->access->token))->true();
        });
    }

        //Аукціон (предоставление юзеру ссылки на торги которые происходят в базе) [1]

    public function testGetAuctionPublicUrl()
    {
        sleep(60*7);
        $el = $this->getLastElement();
        $id = $el['id'];
        $req = $this->api->getAuctionPublicUrl($id);

        $this->specify('check url is', function () use ($req) {
            expect('isset url', $req != '')->true();
        });
    }

    public function testGetAuctionPrivateUrl()
    {
        $el = $this->getLastElement();
        $id = $el['id'];
        $bid_id = $el[Proelements::TYPE_BID][0]['id'];
        $token = $el[Proelements::TYPE_BID][0]['token'];

        $req = $this->api->getAuctionPrivateUrl($id,$bid_id,$token);

        $this->specify('check url is', function () use ($req) {
            expect('isset url', $req != '')->true();
        });
    }

        //Підтвердження кваліфікації (тут не ясно идет речь о Кваліфікаційна комісія) [1]
        //
    public function testAwardUpdate()
    {
        $el = $this->getLastElement();
        $id = $el['id'];
        $token = $el['token'];

        $data = [
            'data' => [
                'status' => 'active',
            ],
        ];

        $req = $this->api->getAuction($id);

        echo '<pre>';
        print_r($req);

        $req = $this->api->updateAward($id,$data,$token);

        print_r($req);
        die;

        $this->specify('check is award update', function () use ($req) {
            expect('status is active', $req->data->status == 'active')->true();
        });
    }

        //Встановлення вартості угоди  [1]
        //Встановлення дати підписання угоди [1]
        //Встановлення терміну дії угоди [1]
        //Завантаження документів щодо укладання угоди [1]
        //Встановити дату підписання договору [1]
        //Реєстрація угоди [1]

    public function testAddContract()
    {
        $el = $this->getLastElement();
        $id = $el['id'];
        $token = $el['token'];

        $req = $this->api->addContract($id,$data,$token);

        $this->specify('check is bid added', function () use ($req) {
            //TODO заменить условие на подходящее
            expect('isset token', isset($req->access->token))->true();
        });
    }

    /*public function testContractUpdate()
    {
        $req = $this->api->updateContract($auction_id,$contract_id,$data,$token);

        $this->specify('check is bid update', function () use ($req) {
            //TODO заменить условие на подходящее
            expect('isset modified field', isset($req->data->tenderPeriod->endDate))->true();
        });
    }*/

    /*public function testContractAddFile()
    {
        $req = $this->api->addFileToContract($auction_id,$contract_id,$file,$token);

        $this->specify('check is file uploded', function () use ($req) {
            expect('isset file url', isset($req->data->url))->true();
        });
    }*/

        //скасування
            //Формування запиту на скасування  [1]
            //Наповнення протоколом та іншою супровідною документацією [2]
            //Активація запиту та скасування закупівлі [1]
    //
    //
    //
    //

    public function testAddCansellation()
    {
        $el = $this->getLastElement();
        $id = $el['id'];
        $token = $el['token'];

        $data = [
            'data' => [
                'reason' => 'все куплено',
            ],
        ];

        $req = $this->api->addCansellation($id,$data,$token);

        Proelements::push(
            Proelements::TYPE_CANCELLATION,
            $req->data->id,
            false,
            $id
        );

        $this->specify('check is cansellation added', function () use ($req) {
            expect('isset id', isset($req->data->id))->true();
        });
    }

    public function testCansellationUpdate()
    {
        $el = $this->getLastElement();
        $id = $el['id'];
        $token = $el['token'];
        $cans_id = $el[Proelements::TYPE_CANCELLATION][0]['id'];

        $data = [
            'data' => [
                'status' => 'active',
            ],
        ];

        $req = $this->api->updateCansellation($id,$cans_id,$data,$token);

        $this->specify('check is cansellation update', function () use ($req) {
            expect('isset cansellation field', isset($req->data->status))->true();
        });
    }

    public function testCansellationAddFile()
    {
        $file = '/var/www/site/tests/codeception/_data/testfile.jpg';
        $el = $this->getLastElement();
        $id = $el['id'];
        $token = $el['token'];
        $cans_id = $el[Proelements::TYPE_CANCELLATION][0]['id'];

        $req = $this->api->addFileToCansellation($id,$cans_id,$file,$token);

        Proelements::push(
            Proelements::TYPE_DOC,
            $req->data->id,
            false,
            $cans_id
        );

        $this->specify('check is file uploded', function () use ($req) {
            expect('isset file url', isset($req->data->url))->true();
        });
    }

        //система оскаржень [6]

    //
    //
    //
    //
    //
    //

    /*public function testAddComplaint()
    {
        $req = $this->api->addComplaint($auction_id,$data,$token);

        $this->specify('check is bid added', function () use ($req) {
            //TODO заменить условие на подходящее
            expect('isset token', isset($req->access->token))->true();
        });
    }*/

    /*public function testComplaintUpdate()
    {
        $req = $this->api->updateComplaint($auction_id,$contract_id,$data,$token);

        $this->specify('check is bid update', function () use ($req) {
            //TODO заменить условие на подходящее
            expect('isset modified field', isset($req->data->tenderPeriod->endDate))->true();
        });
    }

    public function testComplaintAddFile()
    {
        $req = $this->api->addFileToComplaint($auction_id,$contract_id,$file,$token);

        $this->specify('check is file uploded', function () use ($req) {
            expect('isset file url', isset($req->data->url))->true();
        });
    }*/

        //перегляд элементів
            //закупівель, [2]
    /**
     * undocumented function
     *
     * @return void
     */
    public function testShowAuctions()
    {
        $req = $this->api->getAuctions();

        $this->specify('check all auctions list', function () use ($req) {
            expect('data count > 0', (count($req->data) > 0))->true();
        });
    }

    public function testShowAuction()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuction($id);

        $this->specify('check auction data', function () use ($req) {
            expect('isset status', isset($req->data->status))->true();
        });
    }

            //лотів, [1]
            //пропозицій [1]
            //скасування  [1]
            //оскарження [2]
            //документів [1]
    public function testShowAuctionLots()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionLots($id);

        $this->specify('check all auction lots', function () use ($req) {
            expect('check isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionDocs()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionDocs($id);

        $this->specify('check all auction docs', function () use ($req) {
            expect('check isset data', isset($req->data))->true();
        });
    }
            //уточнення [1]
    public function testShowAuctionQuestions()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionQuestions($id);

        $this->specify('check all auction questions', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionAnswer()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $question_id = Options::Pull('taquest');
        $req = $this->api->getAuctionAnswer($id,$question_id);

        $this->specify('check all auction answers', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionBids()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionBids($id);

        $this->specify('check all auction bids', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionComplaints()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionComplaints($id);

        $this->specify('check all auction complaints', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionAwards()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionAwards($id);

        $this->specify('check all auction Awards', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionContracts()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionContracts($id);

        $this->specify('check all auction Contracts', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }

    public function testShowAuctionCancellations()
    {
        $last = $this->getLastElement();
        $id = $last['id'];
        $req = $this->api->getAuctionCancellations($id);

        $this->specify('check all auction Cancellations', function () use ($req) {
            expect('isset data', isset($req->data))->true();
        });
    }
}
