<?php

namespace myPhp;

use GuzzleHttp\Client;

class LinkedIn  
{
    protected $app_id;
    protected $app_secret;
    protected $callback;
    protected $csrf;
    protected $scopes;
    protected $ssl;
    public function __construct(string $app_id, string $app_secret, string $callback, string $scopes, bool $ssl = true)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->scopes =  $scopes;
        $this->csrf = random_int(111111, 999999999);
        $this->callback = $callback;
        $this->ssl = $ssl;
    }
    public function getAuthUrl()
    {
        $_SESSION['linkedincsrf']  = $this->csrf;
        return "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=". $this->app_id . "&redirect_uri=".$this->callback ."&state=". $this->csrf."&scope=". $this->scopes ;
    }
    public function getAccessToken($code)
    {
        $url = "https://www.linkedin.com/oauth/v2/accessToken";
        $params = [
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'redirect_uri' => $this->callback,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $response = $this->curl($url,http_build_query($params), "application/x-www-form-urlencoded");
        $accessToken = json_decode($response)->access_token;
        return $accessToken;
    }
    public function getPerson($accessToken)
    {
        // we will use parameter projection that will include ~:playableStreams
        $url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))&oauth2_access_token=" . $accessToken;
        $params = [];
        // false : for useing GET Request
        $response = $this->curl($url,http_build_query($params), "application/x-www-form-urlencoded", false);
        $person = json_decode($response);
        return $person;
    }
    public function getPersonID($accessToken)
    {
        $url = "https://api.linkedin.com/v2/me?oauth2_access_token=" . $accessToken;
        $params = [];
        // false : for useing GET Request        
        $response = $this->curl($url,http_build_query($params), "application/x-www-form-urlencoded", false);
        $personID = json_decode($response)->id;
        return $personID;
    }
    public function getCompanyPages($accessToken)
    {

        $company_pages = "https://api.linkedin.com/v1/companies?format=json&is-company-admin=true&oauth2_access_token=" . trim($accessToken);
        $pages = $this->curl($company_pages,json_encode([]), "application/json", false);
        return json_decode($pages);
        
    }
    public function linkedInTextPost($accessToken , $person_id,  $message, $visibility = "PUBLIC")
    {
        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "NONE",
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility,
            ]
        ];
        $post = $this->curl($post_url,json_encode($request), "application/json", true);
        return $post;
    }
    public function linkedInLinkPost($accessToken, $person_id, $message, $link_title, $link_desc, $link_url , $visibility = "PUBLIC")
    {
        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "ARTICLE",
                    "media"=> [[
                                            "status" => "READY",
                                            "description"=> [
                                                "text" => substr($link_desc, 0, 200),
                                            ],
                                            "originalUrl" =>  $link_url,
                    
                                            "title" => [
                                                "text" => $link_title,
                                            ],
                                        ]],
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility,
            ]
        ];

        $post = $this->curl($post_url,json_encode($request), "application/json", true);
        return $post;
    }
    public function linkedInPhotoPost($accessToken,   $person_id, $message, $image_path,  $image_title, $image_description , $visibility = "PUBLIC")
    {

        $prepareUrl = "https://api.linkedin.com/v2/assets?action=registerUpload&oauth2_access_token=" .$accessToken;
        $prepareRequest =  [
            "registerUploadRequest" => [
                "recipes" => [
                    "urn:li:digitalmediaRecipe:feedshare-image"
                ],
                "owner" => "urn:li:person:" . $person_id,
                "serviceRelationships" => [
                    [
                        "relationshipType" => "OWNER",
                        "identifier" => "urn:li:userGeneratedContent"
                    ],
                ],
            ],
        ];

        $prepareReponse = $this->curl($prepareUrl,json_encode($prepareRequest), "application/json");
        $uploadURL = json_decode($prepareReponse)->value->uploadMechanism->{"com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest"}->uploadUrl;
        $asset_id = json_decode($prepareReponse)->value->asset;
 ;
        // dump($photo);

        
        $client =new Client();
        $response = $client->request('PUT', $uploadURL, [
            'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ],
            'body' => fopen($image_path, 'r'),
            'verify' => $this->ssl
        ]);

        // dump($response);


        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "IMAGE",
                    "media"=> [[
                                            "status" => "READY",
                                            "description"=> [
                                                "text" => substr($image_description, 0, 200),
                                            ],
                                            "media" =>  $asset_id,
                    
                                            "title" => [
                                                "text" => $image_title,
                                            ],
                                        ]],
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility ,
            ]
        ];

        $post = $this->curl($post_url,json_encode($request), "application/json");
        // dd($post);
        return $post;
    }
    public function curl($url, $parameters, $content_type, $post = true)
    {
        $ch = curl_init();
        // Set URL and other appropriate options Using curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url); // The URL to fetch.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        // TRUE to return the transfer as a string of the return value of curl_exec()
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        }
        curl_setopt($ch, CURLOPT_POST, $post); // TRUE to do a regular HTTP POST. This POST is the 
        $headers = [];
        $headers[] = "Content-Type: {$content_type}";
        // An array of HTTP header fields to set
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Execute The Given cURL session
        $result = curl_exec($ch); // grab URL and pass it to the browser
        return $result;
    }
}
