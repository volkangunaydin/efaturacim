<?php

namespace Efaturacim\Util\Orkestra\Soap\Services;

use Efaturacim\Util\Orkestra\Soap\Result\OrkestraSoapResult;
use Efaturacim\Util\Orkestra\Soap\Util\OrkestraGetPage;
use Efaturacim\Util\Orkestra\XML\ValidateUserPass;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;

class OrkestraFactoryWebService extends OrkestraSoapServiceBase{
    protected $serviceName = "factory";
    public function checkUserNameAndPassword($user,$pass,$getDetails=true){
        $r = new OrkestraSoapResult();
        if(StrUtil::notEmpty($user) && StrUtil::notEmpty($pass)){
            $xml       = ValidateUserPass::xml($user,$pass);
            $resCurl   = $this->curlExec(null,"validateUserPass",$xml,null,true,null,true,null,null);
            $validated = $resCurl->getStringInBetweenTags("Validated");
            if($validated == "true"){
                $r->setIsOk(true);
                $r->addSuccess("Kullanıcı adı ve şifre doğru.");
                $r->setAttribute("userName",$user);
                $r->setAttribute("userPass",$pass);
            }else{
                $r->addError("Kullanıcı adı veya şifre yanlış.");
            }
            //\Vulcan\V::dump($resCurl);
        }else{
            $r->addError("Kullanıcı adı veya şifre boş olamaz.");
        }
        return $r;
    }
    /**
     * @param string $objectNameOrClass
     * @param int $pageIndex
     * @param int $pageSize
     * @param array $options
     * @return OrkestraGetPage
     */
    public function newGetPageList($objectNameOrClass,$pageIndex=1,$pageSize=100,$options=null){
        return new OrkestraGetPage($this,$objectNameOrClass,$pageIndex,$pageSize,$options);
    }
}
?>