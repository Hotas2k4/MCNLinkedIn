<?php
/**
 * Copyright (c) 2011-2013 Antoine Hedgecock.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace MCNLinkedIn\Service;

use MCNLinkedIn\Options\ApiServiceOptions;
use Zend\Http\Response;
use Zend\Math\Rand;
use Zend\Http\Client as HttpClient;

/**
 * Class Api
 * @package MCNLinkedIn\Service
 */
class Api
{
    const URI_OAUTH2_AUTH  = 'https://www.linkedin.com/uas/oauth2/authorization';
    const URI_OAUTH2_TOKEN = 'https://www.linkedin.com/uas/oauth2/accessToken';

    const URI_PROFILE = 'https://api.linkedin.com/v1/people/~';

    const SCOPE_EMAIL                = 'r_emailaddress';
    const SCOPE_BASIC_PROFILE        = 'r_basicprofile';
    const SCOPE_FULL_PROFILE         = 'r_fullprofile';
    const SCOPE_NETWORK              = 'r_network';
    const SCOPE_CONTACT_INFO         = 'r_contactinfo';
    const SCOPE_NETWORK_UPDATES      = 'rw_nus';
    const SCOPE_GROUP_DISCUSSIONS    = 'rw_groups';
    const SCOPE_INVITES_AND_MESSAGES = 'w_message';

    /**
     * @var \Zend\Http\Client
     */
    protected $client;

    /**
     * @var \MCNLinkedIn\Options\ApiServiceOptions
     */
    protected $options;

    /**
     * @param ApiServiceOptions $options
     */
    public function __construct(ApiServiceOptions $options = null)
    {

        $this->options = ($options === null) ? new ApiServiceOptions : $options;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if ($this->client === null) {
            $this->client = new HttpClient(null, array('sslverifypeer' => false));
        }

        return $this->client;
    }

    /**
     * @param HttpClient $client
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return ApiServiceOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @see \MCNLinkedIn\Authentication\Adapter\LinkedIn
     *
     * @return string
     */
    public function getOAuth2Uri($csrf = null)
    {
        $csrf  = ($csrf === null) ? Rand::getString(15) : $csrf;
        $scope = implode(' ', array_unique($this->options->getScope()));

        return static::URI_OAUTH2_AUTH . '?' . http_build_query(
            array(
                'response_type' => 'code',
                'client_id'     => $this->options->getKey(),
                'scope'         => $scope,
                'state'         => $csrf,
                'redirect_uri'  => $this->options->getAuthenticationEndPoint()
            )
        );
    }

    /**
     * Convert an access code to a token
     *
     * @param string $code
     *
     * @throws Exception\ApiException
     * @throws Exception\InvalidResponseException
     *
     * @return \stdClass
     */
    public function requestAccessToken($code)
    {
        $client = $this->getHttpClient();

        $client->setUri(static::URI_OAUTH2_TOKEN);
        $client->setParameterGet(
            array(
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->options->getKey(),
                'client_secret' => $this->options->getSecret(),
                'redirect_uri'  => $this->options->getAuthenticationEndPoint()
            )
        );

        $response = $client->send();

        if ($response->getStatusCode() == 200) {

            return json_decode($response->getBody());
        }

        $this->fail($response);
    }

    /**
     * @param $response
     *
     * @throws Exception\ApiException
     * @throws Exception\InvalidResponseException
     */
    private function fail(Response $response)
    {
        $data = json_decode($response->getBody());

        if (!$data) {

            throw new Exception\ApiException($data['error_description'], $data['error']);
        } else {

            throw new Exception\InvalidResponseException($response->getBody());
        }
    }

    /**
     * @todo use Zend\Http\Client when linkedIn fixes their dam api
     *
     * @codeCoverageIgnore
     *
     * @return \stdClass
     */
    public function getProfile()
    {
        $uri = sprintf(
            '%s:(%s)?format=json&oauth2_access_token=%s',
            static::URI_PROFILE,
            implode(',', $this->options->getProfileFields()),
            $this->options->getAccessToken()
        );

        $response = file_get_contents($uri);
        return json_decode($response);
    }
}
