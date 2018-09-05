<?php
/**
 * This is a PHP library that handles calling reCAPTCHA.
 *
 * @copyright Copyright (c) 2015, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ReCaptcha;

/**
 * The response returned from the service.
 */
class Response
{
    /**
     * Success or failure.
     * @var boolean
     */
    private $success = false;
    private $arRawResponse = array();

    /**
     * Error code strings.
     * @var array
     */
    private $errorCodes = array();

    /**
     * The hostname of the site where the reCAPTCHA was solved.
     * @var string
     */
    private $hostname;

    /**
     * Build the response from the expected JSON returned by the service.
     *
     * @param string $json
     * @return \ReCaptcha\Response
     */
    public static function fromJson($json)
    {
        $responseData = json_decode($json, true);
		
        if (!$responseData) {
            return new Response(false, array('invalid-json'));
        }

        $hostname = isset($responseData['hostname']) ? $responseData['hostname'] : null;

		$iScore = isset($responseData['score']) ? $responseData['score'] : false;
		
		$sAction = isset($responseData['action']) ? $responseData['action'] : false;
		
		$sChallenge_ts = isset($responseData['challenge_ts']) ? $responseData['challenge_ts'] : false;
		
        if (isset($responseData['success']) && $responseData['success'] == true) {
            return new Response(true, array(), $hostname, $iScore, $sAction, $sChallenge_ts, $responseData);
        }

        if (isset($responseData['error-codes']) && is_array($responseData['error-codes'])) {
            return new Response(false, $responseData['error-codes'], $hostname, $iScore, $sAction, $sChallenge_ts, $responseData);
        }

        return new Response(false, array(), $hostname, $iScore, $sAction, $sChallenge_ts, $responseData);
    }

    /**
     * Constructor.
     *
     * @param boolean $success
     * @param array $errorCodes
     * @param string $hostname
     */
    public function __construct($success, array $errorCodes = array(), $hostname = null, $iScore = 0, $sAction = '', $sChallenge_ts = '', $responseData = array())
    {
        $this->success = $success;
        $this->errorCodes = $errorCodes;
        $this->hostname = $hostname;
        $this->score = $iScore;
        $this->action = $sAction;
        $this->challenge_ts = $sChallenge_ts;
		$this->arRawResponse = $responseData;
    }

    public function getRawResponse()
    {
        return $this->arRawResponse;
    }	
	
    /**
     * Is success?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    /**
     * Get hostname.
     *
     * @return string
     */
    public function getHostname()
    {
      return $this->hostname;
    }
}
