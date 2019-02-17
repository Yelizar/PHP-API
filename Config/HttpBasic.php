<?php

namespace Api\Config;

use Api\Storage\User;
use LogicException;

/**
 * Validate a client via Http Basic authentication
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 */
class HttpBasic
{
    private $userData;
    protected $config;
    protected $request;
    public $User;

    public function __construct(array $config = array())
    {
        $this->request = Main::processedRequest();
        $this->User = new User();
        $this->config = array_merge(array(
            'allow_credentials_in_request_body' => true,
            'allow_public_clients' => false,
        ), $config);

    }

    public function validateRequest(Response $resp)
    {
        $response = $resp;
        if (!$userData = $this->getUserCredentials($response)) {
            return false;
        }

        if (!isset($userData['username'])) {
            throw new LogicException('the userData array must have "username" set');
        }

        if (!isset($userData['password']) || $userData['password'] == '') {
            if (!$this->config['allow_public_clients']) {
                $response->setError(400, 'invalid_client', 'client credentials are required');

                return false;
            }

        }

        elseif (!$userInfo = $this->User->checkUserCredentials($userData['username'], $userData['password'])) {

            $response->setError(400, 'invalid_client', 'The client credentials are invalid');
            return false;
        }
        $this->userData = $userData;
        return $userInfo;
    }


    public function getUserId()
    {
        return $this->userData['username'];
    }

    public function getUserCredentials(ResponseInterface $response = null)
    {
        if (!is_null($this->request->headers('PHP_AUTH_USER')) && !is_null($this->request->headers('PHP_AUTH_PW'))) {
            return array('username' => $this->request->headers('PHP_AUTH_USER'), 'password' => $this->request->headers('PHP_AUTH_PW'));
        }

        if ($this->config['allow_credentials_in_request_body']) {
            // Using POST for HttpBasic authorization is not recommended, but is supported by specification
            if (!is_null($this->request->request('username'))) {
                /**
                 * password can be null if the client's password is an empty string
                 * @see http://tools.ietf.org/html/rfc6749#section-2.3.1
                 */
                return array('username' => $this->request->request('username'), 'password' => $this->request->request('password'));
            }
        }

        if ($response) {
            $message = $this->config['allow_credentials_in_request_body'] ? ' or body' : '';
            $response->setError(400, 'invalid_client', 'Client credentials were not found in the headers'.$message);
        }

        return null;
    }
}
