<?php

namespace Efaturacim\Util\Tests\Utils;

use Efaturacim\Util\Utils\RestApiClient;
use Efaturacim\Util\Utils\RestApiResult;
use PHPUnit\Framework\TestCase;

/**
 * A test-specific version of RestApiClient that allows mocking the getJsonResult method.
 */
class RestApiClientTestable extends RestApiClient{
    public static ?RestApiResult $mockResult = null;

    public static function getJsonResult($baseApiUrl, $relPath, $postParams = null, $options = null): RestApiResult
    {
        if (self::$mockResult instanceof RestApiResult) {
            return self::$mockResult;
        }
        // Fallback to an empty, failed result if no mock is provided.
        return new RestApiResult();
    }
}

/**
 * @covers \Efaturacim\Util\RestApiClient
 */
class RestApiClientLoginTest extends TestCase{
    public static $BASE_URL_FOR_TEST = "https://eservistest.orkestra.com.tr/";
    protected function tearDown(): void
    {
        // Her test çalışmadan önce izole bir şekilde tearDown fonksiyonu çağırılıyor. 
        // Bu aşamada özellikle mockResult vb.  alanların sıfırlanması lazım
        RestApiClientTestable::$mockResult = null;
        RestApiClient::setBearer(null);
        parent::tearDown();
    }

    public function testGetLoginSuccess()
    {

        $jsonString = '{
    "isok": true,
    "statuscode": 200,
    "value": 5,
    "data": null,
    "lines": {
        "5": {
            "ref": 5,
            "kod": "Test 31",
            "aciklama": "TEST 31 A.Ş.",
            "vkn": "3333333331",
            "wl_efatura": [],
            "wl_earsiv": [
                4
            ],
            "wl_eirsaliye": [
                183,
                185
            ],
            "wl_emustahsil": [
                210
            ]
        },
        "7": {
            "ref": 7,
            "kod": "TEST25",
            "aciklama": "TEST25 A.Ş.",
            "vkn": "3333333325",
            "wl_efatura": [],
            "wl_earsiv": [],
            "wl_eirsaliye": [
                295
            ],
            "wl_emustahsil": []
        },
        "136": {
            "ref": 136,
            "kod": "sahisfirmasi",
            "aciklama": "Şahıs Firması",
            "vkn": "12345678901",
            "wl_efatura": [],
            "wl_earsiv": [
                197
            ],
            "wl_eirsaliye": [],
            "wl_emustahsil": []
        }
    },
    "attributes": {
        "user_reference": 5,
        "firma_ref": 5,
        "firma_vkn": "3333333331",
        "firma_unvan": "TEST 31 A.Ş.",
        "bearer": "6D29C689-AB79-4E39-BB4C-166AA79B53A6",
        "device": "GUID-GUID-GUID-0002"
    },
    "messages": [
        {
            "text": "Kullanıcı girişi başarılı.",
            "type": "success",
            "t": "2025-07-20 09:28:16"
        }
    ],
    "start": "2025-07-20 09:28:16",
    "microtime": 1752992896.803888,
    "elapsed": 0.217315,
    "apiMsgGuid": "92CD7194-8504-41A1-AAD1-C01E7E29D1AC",
    "called_url": "http://eservistest.localhost.com/EFaturacim/Login?user=aebrar&customer=ayselebrar&pass=***&clientInfo=CLIENT+INFO+STR&clientSecret=GUID-GUID-GUID-0002&ip=11.12.13.14"
}';
        RestApiClientTestable::$mockResult = RestApiResult::newFromJson($jsonString);

        // Act: Call the function we are testing
        $loginResult = RestApiClientTestable::getLogin('http://fake-api.com', '/login');
        $mockBearerToken = '6D29C689-AB79-4E39-BB4C-166AA79B53A6';
        // Assert: Verify the outcome
        $this->assertTrue($loginResult->isOK());
        $this->assertEquals($mockBearerToken, $loginResult->getAttribute('bearer'));        
    }

    public function testGetLoginFailureWhenApiReturnsSuccessButInvalidData()
    {
        $jsonString = '{ "isok":false,"statusCode":401 }';        
        // Act
        $loginResult = RestApiResult::newFromJson($jsonString);
        // Assert
        $this->assertFalse($loginResult->isOK());
        $this->assertEquals(401, $loginResult->statusCode);
        //$this->assertStringContainsString('Kullanıcı doğrulanamadı.', $loginResult->getMessages());
        $this->assertNull(RestApiClient::$DEFAULT_BEARER_TOKEN);
    }

    public function testGetLoginFailureWhenApiReturnsError()
    {
        // Act
        $jsonString = '{  }';
        $loginResult = RestApiResult::newFromJson($jsonString);
        // Assert
        $this->assertFalse($loginResult->isOK());     
    }
}