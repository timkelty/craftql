<?php

namespace markhuot\CraftQL\Models;

use craft\base\Model;
use markhuot\CraftQL\Models\Token;

class Settings extends Model
{
    public $graphiqlFetchUrl = null; // defaults to siteUrl via CpController
    public $uri = 'api';
    public $verbs = ['POST'];
    public $allowedOrigins = [];
    public $allowedHeaders = ['Authorization, Content-Type'];
    public $headers = [];
    public $maxQueryDepth = false;
    public $maxQueryComplexity = false;
    public $cacheEnabled = false;
    public $cacheDuration = null;
    public $logQueries = false;
    public $logCacheTags = false;
    public $throwSchemaBuildErrors = false;

    function rules()
    {
        return [
            [['uri'], 'required'],
            // ...
        ];
    }

    function tokens()
    {
        $tokens = Token::find()->where(['userId' => \Craft::$app->user->id])->all();
        return $tokens;
    }
}
