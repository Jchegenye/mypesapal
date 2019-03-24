<?php

namespace Jchegenye\MyPesaPal\JTech;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

    class PesaPalIframe
    {

        /**
         * Process pesapal
         *
         * @param  array $formData, $value
         * @param  key  $key
         * @return \Jchegenye\MyPesaPal\JTech\PesaPalIframe
         */
        public function processFormData($formData)
        {

            //pesapal params
            $token = $params = Config::get('pesapal.auth.token_params');

            $consumer_key = Config::get('pesapal.auth.consumer_key');
            $consumer_secret = Config::get('pesapal.auth.consumer_secret');

            $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();

            if (\App::environment('local')) {
                $iframelink = Config::get('pesapal.auth.demo_iframelink');
            } elseif (\App::environment('production')) {
                $iframelink = Config::get('pesapal.auth.live_iframelink');
            }

            //Construct the post_xml variable - The format is standard so no editing is required. Encode the variable using htmlentities.
            $selectedFields = Config::get('pesapal.fields');
            
            $globalXML = '';
            foreach ((array)$formData->request as $data) {
                
                foreach($data as $key => $value){

                    if (array_key_exists($key, $selectedFields)) {
                        if (count($selectedFields) > 1) {

                            $globalXML .= preg_replace('/\s+/', '', str_replace('_', ' ', ucwords($key)))."="."\"".$value."\" ";
                           
                        } 
                    }

                }

            }

            $callback_url = Config::get('pesapal.callback_url'); //redirect url, the page that will handle the response from pesapal.
            
            $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" $globalXML xmlns=\"http://www.pesapal.com\" />";
            $post_xml = htmlentities($post_xml);
            //echo '<pre>' . var_export($post_xml, true) . '</pre>';

            //
            $consumer = new \OAuthConsumer($consumer_key, $consumer_secret);

            //post transaction to pesapal
            $iframe_src = \OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
            $iframe_src->set_parameter("oauth_callback", $callback_url);
            $iframe_src->set_parameter("pesapal_request_data", $post_xml);
            $iframe_src->sign_request($signature_method, $consumer, $token);
            //echo '<pre>' . var_export($iframe_src, true) . '</pre>';

            ?>

            <iframe src="<?php echo $iframe_src;?>" width="100%" height="700px"  scrolling="no" frameBorder="0">
                <p>Browser unable to load iFrame</p>
            </iframe>

            <?php

        }

    }
    