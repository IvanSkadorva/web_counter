<?php


class BrowserDetection
{

    const BROWSER_AMAYA = 'Amaya';
    const BROWSER_ANDROID = 'Android';
    const BROWSER_BINGBOT = 'Bingbot';
    const BROWSER_BLACKBERRY = 'BlackBerry';
    const BROWSER_CHROME = 'Chrome';
    const BROWSER_EDGE = 'Edge';
    const BROWSER_FIREBIRD = 'Firebird';
    const BROWSER_FIREFOX = 'Firefox';
    const BROWSER_GALEON = 'Galeon';
    const BROWSER_GOOGLEBOT = 'Googlebot';
    const BROWSER_ICAB = 'iCab';
    const BROWSER_ICECAT = 'GNU IceCat';
    const BROWSER_ICEWEASEL = 'GNU IceWeasel';
    const BROWSER_IE = 'Internet Explorer';
    const BROWSER_IE_MOBILE = 'Internet Explorer Mobile';
    const BROWSER_KONQUEROR = 'Konqueror';
    const BROWSER_LYNX = 'Lynx';
    const BROWSER_MOZILLA = 'Mozilla';
    const BROWSER_MSNBOT = 'MSNBot';
    const BROWSER_MSNTV = 'MSN TV';
    const BROWSER_NETPOSITIVE = 'NetPositive';
    const BROWSER_NETSCAPE = 'Netscape';
    const BROWSER_NOKIA = 'Nokia Browser';
    const BROWSER_OMNIWEB = 'OmniWeb';
    const BROWSER_OPERA = 'Opera';
    const BROWSER_OPERA_MINI = 'Opera Mini';
    const BROWSER_OPERA_MOBILE = 'Opera Mobile';
    const BROWSER_PHOENIX = 'Phoenix';
    const BROWSER_SAFARI = 'Safari';
    const BROWSER_SAMSUNG = 'Samsung Internet';
    const BROWSER_SLURP = 'Yahoo! Slurp';
    const BROWSER_TABLET_OS = 'BlackBerry Tablet OS';
    const BROWSER_UNKNOWN = 'unknown';
    const BROWSER_VIVALDI = 'Vivaldi';
    const BROWSER_W3CVALIDATOR = 'W3C Validator';
    const BROWSER_YAHOO_MM = 'Yahoo! Multimedia';
    const PLATFORM_ANDROID = 'Android';
    const PLATFORM_BEOS = 'BeOS';
    const PLATFORM_BLACKBERRY = 'BlackBerry';
    const PLATFORM_FREEBSD = 'FreeBSD';
    const PLATFORM_IPAD = 'iPad';
    const PLATFORM_IPHONE = 'iPhone';
    const PLATFORM_IPOD = 'iPod';
    const PLATFORM_LINUX = 'Linux';
    const PLATFORM_MACINTOSH = 'Macintosh';
    const PLATFORM_NETBSD = 'NetBSD';
    const PLATFORM_NOKIA = 'Nokia';
    const PLATFORM_OPENBSD = 'OpenBSD';
    const PLATFORM_OPENSOLARIS = 'OpenSolaris';
    const PLATFORM_OS2 = 'OS/2';
    const PLATFORM_SUNOS = 'SunOS';
    const PLATFORM_SYMBIAN = 'Symbian';
    const PLATFORM_UNKNOWN = 'unknown';
    const PLATFORM_VERSION_UNKNOWN = 'unknown';
    const PLATFORM_WINDOWS = 'Windows';
    const PLATFORM_WINDOWS_CE = 'Windows CE';
    const PLATFORM_WINDOWS_PHONE = 'Windows Phone';

    const VERSION_UNKNOWN = 'unknown';


     
    private $_agent = '';

     
    private $_aolVersion = '';

     
    private $_browserName = '';

     
    private $_compatibilityViewName = '';

     
    private $_compatibilityViewVer = '';

     
    private $_is64bit = false;

     
    private $_isAol = false;

     
    private $_isMobile = false;

     
    private $_isRobot = false;

     
    private $_platform = '';

     
    private $_platformVersion = '';

     
    private $_version = '';


    //--- MAGIC METHODS ------------------------------------------------------------------------------------------------


     
    public function __construct($useragent = '')
    {
        $this->setUserAgent($useragent);
    }

     
    public function __toString()
    {
        $result = '';

        $values = array();
        $values[] = array('label' => 'User agent', 'value' => $this->getUserAgent());
        $values[] = array('label' => 'Browser name', 'value' => $this->getName());
        $values[] = array('label' => 'Browser version', 'value' => $this->getVersion());
        $values[] = array('label' => 'Platform family', 'value' => $this->getPlatform());
        $values[] = array('label' => 'Platform version', 'value' => $this->getPlatformVersion(true));
        $values[] = array('label' => 'Platform version name', 'value' => $this->getPlatformVersion());
        $values[] = array('label' => 'Platform is 64-bit', 'value' => $this->is64bitPlatform() ? 'true' : 'false');
        $values[] = array('label' => 'Is mobile', 'value' => $this->isMobile() ? 'true' : 'false');
        $values[] = array('label' => 'Is robot', 'value' => $this->isRobot() ? 'true' : 'false');
        $values[] = array('label' => 'IE is in compatibility view', 'value' => $this->isInIECompatibilityView() ? 'true' : 'false');
        $values[] = array('label' => 'Emulated IE version', 'value' => $this->isInIECompatibilityView() ? $this->getIECompatibilityView() : 'Not applicable');
        $values[] = array('label' => 'Is Chrome Frame', 'value' => $this->isChromeFrame() ? 'true' : 'false');
        $values[] = array('label' => 'Is AOL optimized', 'value' => $this->isAol() ? 'true' : 'false');
        $values[] = array('label' => 'AOL version', 'value' => $this->isAol() ? $this->getAolVersion() : 'Not applicable');

        foreach ($values as $currVal) {
            $result .= '<strong>' . htmlspecialchars($currVal['label'], ENT_NOQUOTES) . ':</strong> ' . $currVal['value'] . '<br />' . PHP_EOL;
        }

        return $result;
    }


    //--- PUBLIC MEMBERS -----------------------------------------------------------------------------------------------


     
    public function compareVersions($sourceVer, $compareVer)
    {
        $sourceVer = explode('.', $sourceVer);
        foreach ($sourceVer as $k => $v) {
            $sourceVer[$k] = $this->parseInt($v);
        }

        $compareVer = explode('.', $compareVer);
        foreach ($compareVer as $k => $v) {
            $compareVer[$k] = $this->parseInt($v);
        }

        if (count($sourceVer) != count($compareVer)) {
            if (count($sourceVer) > count($compareVer)) {
                for ($i = count($compareVer); $i < count($sourceVer); $i++) {
                    $compareVer[$i] = 0;
                }
            } else {
                for ($i = count($sourceVer); $i < count($compareVer); $i++) {
                    $sourceVer[$i] = 0;
                }
            }
        }

        foreach ($sourceVer as $i => $srcVerPart) {
            if ($srcVerPart > $compareVer[$i]) {
                return 1;
            } else {
                if ($srcVerPart < $compareVer[$i]) {
                    return -1;
                }
            }
        }

        return 0;
    }

     
    public function getAolVersion()
    {
        return $this->_aolVersion;
    }

     
    public function getName()
    {
        return $this->_browserName;
    }

     
    public function getIECompatibilityView($asArray = false)
    {
        if ($asArray) {
            return array('browser' => $this->_compatibilityViewName, 'version' => $this->_compatibilityViewVer);
        } else {
            return trim($this->_compatibilityViewName . ' ' . $this->_compatibilityViewVer);
        }
    }

     
    public function getPlatform()
    {
        return $this->_platform;
    }

     
    public function getPlatformVersion($returnVersionNumbers = false, $returnServerFlavor = false)
    {
        if ($this->_platformVersion == self::PLATFORM_VERSION_UNKNOWN || $this->_platformVersion == '') {
            return self::PLATFORM_VERSION_UNKNOWN;
        }

        if ($returnVersionNumbers) {
            return $this->_platformVersion;
        } else {
            switch ($this->getPlatform()) {
                case self::PLATFORM_WINDOWS:
                    if (substr($this->_platformVersion, 0, 3) == 'NT ') {
                        return $this->windowsNTVerToStr(substr($this->_platformVersion, 3), $returnServerFlavor);
                    } else {
                        return $this->windowsVerToStr($this->_platformVersion);
                    }
                    break;

                case self::PLATFORM_MACINTOSH:
                    return $this->macVerToStr($this->_platformVersion);
                    break;
                    
                case self::PLATFORM_IPAD:
                    return $this->iOSVerToStr($this->_platformVersion);
                    break;
				
				case self::PLATFORM_IPHONE:
                    return $this->iOSVerToStr($this->_platformVersion);
                    break;

				case self::PLATFORM_IPOD:
                    return $this->iOSVerToStr($this->_platformVersion);
                    break;

                case self::PLATFORM_ANDROID:
                    return $this->androidVerToStr($this->_platformVersion);
                    break;

                default: return self::PLATFORM_VERSION_UNKNOWN;
            }
        }
    }

     
    public function getUserAgent()
    {
        return $this->_agent;
    }

     
    public function getVersion()
    {
        return $this->_version;
    }

     
    public function is64bitPlatform()
    {
        return $this->_is64bit;
    }

     
    public function isAol()
    {
        return $this->_isAol;
    }

     
    public function isChromeFrame()
    {
        return stripos($this->_agent, 'chromeframe') !== false;
    }

     
    public function isInIECompatibilityView()
    {
        return ($this->_compatibilityViewName != '') || ($this->_compatibilityViewVer != '');
    }

     
    public function isMobile()
    {
        return $this->_isMobile;
    }

     
    public function isRobot()
    {
        return $this->_isRobot;
    }

     
    public function setUserAgent($agentString = '')
    {
        if (!is_string($agentString) || trim($agentString) == '') {
            if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && is_string($_SERVER['HTTP_USER_AGENT'])) {
                $agentString = $_SERVER['HTTP_USER_AGENT'];
            } else {
                $agentString = '';
            }
        }

        $this->reset();
        $this->_agent = $agentString;
        $this->detect();
    }


    //--- PROTECTED MEMBERS --------------------------------------------------------------------------------------------


     
    protected function androidVerToStr($androidVer)
    {
        //https://en.wikipedia.org/wiki/Android_version_history

        if ($this->compareVersions($androidVer, '7') >= 0 && $this->compareVersions($androidVer, '8') < 0) {
            return 'Nougat';
        } else if ($this->compareVersions($androidVer, '6') >= 0 && $this->compareVersions($androidVer, '7') < 0) {
            return 'Marshmallow';
        } else if ($this->compareVersions($androidVer, '5') >= 0 && $this->compareVersions($androidVer, '6') < 0) {
            return 'Lollipop';
        } else if ($this->compareVersions($androidVer, '4.4') >= 0 && $this->compareVersions($androidVer, '5') < 0) {
            return 'KitKat';
        } else if ($this->compareVersions($androidVer, '4.1') >= 0 && $this->compareVersions($androidVer, '4.4') < 0) {
            return 'Jelly Bean';
        } else if ($this->compareVersions($androidVer, '4') >= 0 && $this->compareVersions($androidVer, '4.1') < 0) {
            return 'Ice Cream Sandwich';
        } else if ($this->compareVersions($androidVer, '3') >= 0 && $this->compareVersions($androidVer, '4') < 0) {
            return 'Honeycomb';
        } else if ($this->compareVersions($androidVer, '2.3') >= 0 && $this->compareVersions($androidVer, '3') < 0) {
            return 'Gingerbread';
        } else if ($this->compareVersions($androidVer, '2.2') >= 0 && $this->compareVersions($androidVer, '2.3') < 0) {
            return 'Froyo';
        } else if ($this->compareVersions($androidVer, '2') >= 0 && $this->compareVersions($androidVer, '2.2') < 0) {
            return 'Eclair';
        } else if ($this->compareVersions($androidVer, '1.6') >= 0 && $this->compareVersions($androidVer, '2') < 0) {
            return 'Donut';
        } else if ($this->compareVersions($androidVer, '1.5') >= 0 && $this->compareVersions($androidVer, '1.6') < 0) {
            return 'Cupcake';
        } else {
            return self::PLATFORM_VERSION_UNKNOWN; //Unknown/unnamed Android version
        }
    }

     
    protected function checkBrowserAmaya()
    {
        return $this->checkSimpleBrowserUA('amaya', $this->_agent, self::BROWSER_AMAYA);
    }

     
    protected function checkBrowserAndroid()
    {
        //Android don't use the standard "Android/1.0", it uses "Android 1.0;" instead
        return $this->checkSimpleBrowserUA('Android', $this->_agent, self::BROWSER_ANDROID, true);
    }

     
    protected function checkBrowserBingbot()
    {
        return $this->checkSimpleBrowserUA('bingbot', $this->_agent, self::BROWSER_BINGBOT, false, true);
    }

     
    protected function checkBrowserBlackBerry()
    {
        $found = false;

        //Tablet OS check
        if ($this->checkSimpleBrowserUA('RIM Tablet OS', $this->_agent, self::BROWSER_TABLET_OS, true)) {
            return true;
        }

        //Version 6, 7 & 10 check (versions 8 & 9 does not exists)
        if ($this->checkBrowserUAWithVersion(array('BlackBerry', 'BB10'), $this->_agent, self::BROWSER_BLACKBERRY, true)) {
            if ($this->getVersion() == self::VERSION_UNKNOWN) {
                $found = true;
            } else {
                return true;
            }
        }

        //Version 4.2 to 5.0 check
        if ($this->checkSimpleBrowserUA('BlackBerry', $this->_agent, self::BROWSER_BLACKBERRY, true)) {
            if ($this->getVersion() == self::VERSION_UNKNOWN) {
                $found = true;
            } else {
                return true;
            }
        }

        return $found;
    }

     
    protected function checkBrowserChrome()
    {
        return $this->checkSimpleBrowserUA('Chrome', $this->_agent, self::BROWSER_CHROME);
    }

     
    protected function checkBrowserEdge()
    {
        return $this->checkSimpleBrowserUA('Edge', $this->_agent, self::BROWSER_EDGE);
    }

     
    protected function checkBrowserFirebird()
    {
        return $this->checkSimpleBrowserUA('Firebird', $this->_agent, self::BROWSER_FIREBIRD);
    }

     
    protected function checkBrowserFirefox()
    {
        //Safari heavily matches with Firefox, ensure that Safari is filtered out...
        if (preg_match('/.*Firefox[ (\/]*([a-z0-9.-]*)/i', $this->_agent, $matches) &&
                stripos($this->_agent, 'Safari') === false) {
            $this->setBrowser(self::BROWSER_FIREFOX);
            $this->setVersion($matches[1]);
            $this->setMobile(false);
            $this->setRobot(false);

            return true;
        }

        return false;
    }

     
    protected function checkBrowserGaleon()
    {
        return $this->checkSimpleBrowserUA('Galeon', $this->_agent, self::BROWSER_GALEON);
    }

     
    protected function checkBrowserGooglebot()
    {
        if ($this->checkSimpleBrowserUA('Googlebot', $this->_agent, self::BROWSER_GOOGLEBOT, false, true)) {
            if (strpos(strtolower($this->_agent), 'googlebot-mobile') !== false) {
                $this->setMobile(true);
            }

            return true;
        }

        return false;
    }

     
    protected function checkBrowserIcab()
    {
        //Some (early) iCab versions don't use the standard "iCab/1.0", they uses "iCab 1.0;" instead
        return $this->checkSimpleBrowserUA('iCab', $this->_agent, self::BROWSER_ICAB);
    }

     
    protected function checkBrowserIceCat()
    {
        return $this->checkSimpleBrowserUA('IceCat', $this->_agent, self::BROWSER_ICECAT);
    }

     
    protected function checkBrowserIceWeasel()
    {
        return $this->checkSimpleBrowserUA('Iceweasel', $this->_agent, self::BROWSER_ICEWEASEL);
    }

     
    protected function checkBrowserInternetExplorer()
    {
        //Test for Internet Explorer Mobile (formerly Pocket Internet Explorer)
        if ($this->checkSimpleBrowserUA(array('IEMobile', 'MSPIE'), $this->_agent, self::BROWSER_IE_MOBILE, true)) {
            return true;
        }

        //Several browsers uses IE compatibility UAs filter these browsers out (but after testing for IE Mobile)
        if ($this->containString($this->_agent, array('Opera', 'BlackBerry', 'Nokia'))) {
            return false;
        }

        //Test for Internet Explorer 1
        if ($this->checkSimpleBrowserUA('Microsoft Internet Explorer', $this->_agent, self::BROWSER_IE)) {
            if ($this->getVersion() == self::VERSION_UNKNOWN) {
                if (preg_match('/308|425|426|474|0b1/i', $this->_agent)) {
                    $this->setVersion('1.5');
                } else {
                    $this->setVersion('1.0');
                }
            }
            return true;
        }

        //Test for Internet Explorer 2+
        if ($this->containString($this->_agent, array('MSIE', 'Trident'))) {
            $version = '';

            if (stripos($this->_agent, 'Trident') !== false) {
                //Test for Internet Explorer 11+ (check the rv: string)
                if (stripos($this->_agent, 'rv:') !== false) {
                    if ($this->checkSimpleBrowserUA('Trident', $this->_agent, self::BROWSER_IE, false, false, 'rv:')) {
                        return true;
                    }
                } else {
                    //Test for Internet Explorer 8, 9 & 10 (check the Trident string)
                    if (preg_match('/Trident\/([\d]+)/i', $this->_agent, $foundVersion)) {
                        //Trident started with version 4.0 on IE 8
                        $verFromTrident = $this->parseInt($foundVersion[1]) + 4;
                        if ($verFromTrident >= 8) {
                            $version = $verFromTrident . '.0';
                        }
                    }
                }

                //If we have the IE version from Trident, we can check for the compatibility view mode
                if ($version != '') {
                    $emulatedVer = '';
                    preg_match_all('/MSIE\s*([^\s;$]+)/i', $this->_agent, $foundVersions);
                    foreach ($foundVersions[1] as $currVer) {
                        //Keep the lowest MSIE version for the emulated version (in compatibility view mode)
                        if ($emulatedVer == '' || $this->compareVersions($emulatedVer, $currVer) == 1) {
                            $emulatedVer = $currVer;
                        }
                    }
                    //Set the compatibility view mode if $version != $emulatedVer
                    if ($this->compareVersions($version, $emulatedVer) != 0) {
                        $this->_compatibilityViewName = self::BROWSER_IE;
                        $this->_compatibilityViewVer = $this->cleanVersion($emulatedVer);
                    }
                }
            }

            //Test for Internet Explorer 2-7 versions if needed
            if ($version == '') {
                preg_match_all('/MSIE\s+([^\s;$]+)/i', $this->_agent, $foundVersions);
                foreach ($foundVersions[1] as $currVer) {
                    //Keep the highest MSIE version
                    if ($version == '' || $this->compareVersions($version, $currVer) == -1) {
                        $version = $currVer;
                    }
                }
            }

            $this->setBrowser(self::BROWSER_IE);
            $this->setVersion($version);
            $this->setMobile(false);
            $this->setRobot(false);

            return true;
        }

        return false;
    }

     
    protected function checkBrowserKonqueror()
    {
        return $this->checkSimpleBrowserUA('Konqueror', $this->_agent, self::BROWSER_KONQUEROR);
    }

     
    protected function checkBrowserLynx()
    {
        return $this->checkSimpleBrowserUA('Lynx', $this->_agent, self::BROWSER_LYNX);
    }

     
    protected function checkBrowserMozilla()
    {
        return $this->checkSimpleBrowserUA('Mozilla', $this->_agent, self::BROWSER_MOZILLA, false, false, 'rv:');
    }

     
    protected function checkBrowserMsnBot()
    {
        return $this->checkSimpleBrowserUA('msnbot', $this->_agent, self::BROWSER_MSNBOT, false, true);
    }

     
    protected function checkBrowserMsnTv()
    {
        return $this->checkSimpleBrowserUA('webtv', $this->_agent, self::BROWSER_MSNTV);
    }

     
    protected function checkBrowserNetPositive()
    {
        return $this->checkSimpleBrowserUA('NetPositive', $this->_agent, self::BROWSER_NETPOSITIVE);
    }

     
    protected function checkBrowserNetscape()
    {
        //BlackBerry & Nokia UAs can conflict with Netscape UAs
        if ($this->containString($this->_agent, array('BlackBerry', 'Nokia'))) {
            return false;
        }

        //Netscape v6 to v9 check
        if ($this->checkSimpleBrowserUA(array('Netscape', 'Navigator', 'Netscape6'), $this->_agent, self::BROWSER_NETSCAPE)) {
            return true;
        }

        //Netscape v1-4 (v5 don't exists)
        $found = false;
        if (stripos($this->_agent, 'Mozilla') !== false && stripos($this->_agent, 'rv:') === false) {
            $version = '';
            $verParts = explode('/', stristr($this->_agent, 'Mozilla'));
            if (count($verParts) > 1) {
                $verParts = explode(' ', $verParts[1]);
                $verParts = explode('.', $verParts[0]);

                $majorVer = $this->parseInt($verParts[0]);
                if ($majorVer > 0 && $majorVer < 5) {
                    $version = implode('.', $verParts);
                    $found = true;

                    if (strtolower(substr($version, -4)) == '-sgi') {
                        $version = substr($version, 0, -4);
                    } else {
                        if (strtolower(substr($version, -4)) == 'gold') {
                            $version = substr($version, 0, -4) . ' Gold'; //Doubles spaces (if any) will be normalized by setVersion()
                        }
                    }
                }
            }
        }

        if ($found) {
            $this->setBrowser(self::BROWSER_NETSCAPE);
            $this->setVersion($version);
            $this->setMobile(false);
            $this->setRobot(false);
        }

        return $found;
    }

     
    protected function checkBrowserNokia()
    {
        if ($this->containString($this->_agent, array('Nokia5800', 'Nokia5530', 'Nokia5230'))) {
            $this->setBrowser(self::BROWSER_NOKIA);
            $this->setVersion('7.0');
            $this->setMobile(true);
            $this->setRobot(false);

            return true;
        }

        if ($this->checkSimpleBrowserUA(array('NokiaBrowser', 'BrowserNG', 'Series60', 'S60', 'S40OviBrowser'), $this->_agent, self::BROWSER_NOKIA, true)) {
            return true;
        }

        return false;
    }

     
    protected function checkBrowserOmniWeb()
    {
        if ($this->checkSimpleBrowserUA('OmniWeb', $this->_agent, self::BROWSER_OMNIWEB)) {
            //Some versions of OmniWeb prefix the version number with "v"
            if ($this->getVersion() != self::VERSION_UNKNOWN && strtolower(substr($this->getVersion(), 0, 1)) == 'v') {
                $this->setVersion(substr($this->getVersion(), 1));
            }
            return true;
        }

        return false;
    }

     
    protected function checkBrowserOpera()
    {
        if ($this->checkBrowserUAWithVersion('Opera Mobi', $this->_agent, self::BROWSER_OPERA_MOBILE, true)) {
            return true;
        }

        if ($this->checkSimpleBrowserUA('Opera Mini', $this->_agent, self::BROWSER_OPERA_MINI, true)) {
            return true;
        }

        $version = '';
        $found = $this->checkBrowserUAWithVersion('Opera', $this->_agent, self::BROWSER_OPERA);
        if ($found && $this->getVersion() != self::VERSION_UNKNOWN) {
            $version = $this->getVersion();
        }

        if (!$found || $version == '') {
            if ($this->checkSimpleBrowserUA('Opera', $this->_agent, self::BROWSER_OPERA)) {
                return true;
            }
        }

        if (!$found && $this->checkSimpleBrowserUA('Chrome', $this->_agent, self::BROWSER_CHROME) ) {
            if ($this->checkSimpleBrowserUA('OPR/', $this->_agent, self::BROWSER_OPERA)) {
                return true;
            }
        }

        return $found;
    }

     
    protected function checkBrowserPhoenix()
    {
        return $this->checkSimpleBrowserUA('Phoenix', $this->_agent, self::BROWSER_PHOENIX);
    }

     
    protected function checkBrowsers()
    {
        //Changing the check order can break the class detection results!
        return
                
               $this->checkBrowserMsnTv() ||             
               $this->checkBrowserInternetExplorer() ||
               $this->checkBrowserOpera() ||             
               $this->checkBrowserEdge() ||              
               $this->checkBrowserVivaldi() ||           
               $this->checkBrowserSamsung() ||           
               $this->checkBrowserChrome() ||            
               $this->checkBrowserOmniWeb() ||           
               $this->checkBrowserIcab() ||              
               $this->checkBrowserNetPositive() ||       
               $this->checkBrowserNetscape() ||          
               $this->checkBrowserIceCat() ||            
               $this->checkBrowserIceWeasel() ||
               $this->checkBrowserGaleon() ||            
               $this->checkBrowserFirefox() ||
                
               $this->checkBrowserKonqueror() ||
               $this->checkBrowserLynx() ||
               $this->checkBrowserAmaya() ||
                
               $this->checkBrowserAndroid() ||
               $this->checkBrowserBlackBerry() ||
               $this->checkBrowserNokia() ||
                
               $this->checkBrowserGooglebot() ||
               $this->checkBrowserBingbot() ||
               $this->checkBrowserMsnBot() ||
               $this->checkBrowserSlurp() ||
               $this->checkBrowserYahooMultimedia() ||
               $this->checkBrowserW3CValidator() ||
                
               $this->checkBrowserSafari() ||
                
               $this->checkBrowserFirebird() ||
               $this->checkBrowserPhoenix() ||
                
               $this->checkBrowserMozilla();
    }

     
    protected function checkBrowserSafari()
    {
        $version = '';

        //Check for current versions of Safari
        $found = $this->checkBrowserUAWithVersion(array('Safari', 'AppleWebKit'), $this->_agent, self::BROWSER_SAFARI);
        if ($found && $this->getVersion() != self::VERSION_UNKNOWN) {
            $version = $this->getVersion();
        }

        //Safari 1-2 didn't had a "Version" string in the UA, only a WebKit build and/or Safari build, extract version from these...
        if (!$found || $version == '') {
            if (preg_match('/.*Safari[ (\/]*([a-z0-9.-]*)/i', $this->_agent, $matches)) {
                $version = $this->safariBuildToSafariVer($matches[1]);
                $found = true;
            }
        }
        if (!$found || $version == '') {
            if (preg_match('/.*AppleWebKit[ (\/]*([a-z0-9.-]*)/i', $this->_agent, $matches)) {
                $version = $this->webKitBuildToSafariVer($matches[1]);
                $found = true;
            }
        }

        if ($found) {
            $this->setBrowser(self::BROWSER_SAFARI);
            $this->setVersion($version);
            $this->setMobile(false);
            $this->setRobot(false);
        }

        return $found;
    }

     
    protected function checkBrowserSamsung()
    {
        return $this->checkSimpleBrowserUA('SamsungBrowser', $this->_agent, self::BROWSER_SAMSUNG, true);
    }

     
    protected function checkBrowserSlurp()
    {
        return $this->checkSimpleBrowserUA('Yahoo! Slurp', $this->_agent, self::BROWSER_SLURP, false, true);
    }

     
    protected function checkBrowserUAWithVersion($uaNameToLookFor, $userAgent, $browserName, $isMobile = false, $isRobot = false)
    {
        if (!is_array($uaNameToLookFor)) {
            $uaNameToLookFor = array($uaNameToLookFor);
        }

        foreach ($uaNameToLookFor as $currUANameToLookFor) {
            if (stripos($userAgent, $currUANameToLookFor) !== false) {
                $version = '';
                $verParts = explode('/', stristr($this->_agent, 'Version'));
                if (count($verParts) > 1) {
                    $verParts = explode(' ', $verParts[1]);
                    $version = $verParts[0];
                }

                $this->setBrowser($browserName);
                $this->setVersion($version);

                $this->setMobile($isMobile);
                $this->setRobot($isRobot);

                return true;
            }
        }

        return false;
    }

     
    protected function checkBrowserVivaldi()
    {
        return $this->checkSimpleBrowserUA('Vivaldi', $this->_agent, self::BROWSER_VIVALDI);
    }

     
    protected function checkBrowserW3CValidator()
    {
        //Since the W3C validates pages with different robots we will prefix our versions with the part validated on the page...

        //W3C Link Checker (prefixed with "Link-")
        if ($this->checkSimpleBrowserUA('W3C-checklink', $this->_agent, self::BROWSER_W3CVALIDATOR, false, true)) {
            if ($this->getVersion() != self::VERSION_UNKNOWN) {
                $this->setVersion('Link-' . $this->getVersion());
            }
            return true;
        }

        //W3C CSS Validation Service (prefixed with "CSS-")
        if ($this->checkSimpleBrowserUA('Jigsaw', $this->_agent, self::BROWSER_W3CVALIDATOR, false, true)) {
            if ($this->getVersion() != self::VERSION_UNKNOWN) {
                $this->setVersion('CSS-' . $this->getVersion());
            }
            return true;
        }

        //W3C mobileOK Checker (prefixed with "mobileOK-")
        if ($this->checkSimpleBrowserUA('W3C-mobileOK', $this->_agent, self::BROWSER_W3CVALIDATOR, false, true)) {
            if ($this->getVersion() != self::VERSION_UNKNOWN) {
                $this->setVersion('mobileOK-' . $this->getVersion());
            }
            return true;
        }

        //W3C Markup Validation Service (no prefix)
        return $this->checkSimpleBrowserUA('W3C_Validator', $this->_agent, self::BROWSER_W3CVALIDATOR, false, true);
    }

     
    protected function checkBrowserYahooMultimedia()
    {
        return $this->checkSimpleBrowserUA('Yahoo-MMCrawler', $this->_agent, self::BROWSER_YAHOO_MM, false, true);
    }

     
    protected function checkForAol()
    {
        //AOL UAs don't use the "AOL/1.0" format, they uses "AOL 1.0; AOLBuild 100.00;"
        if (stripos($this->_agent, 'AOL ') !== false) {
            $version = '';
            $verParts = explode('AOL ', stristr($this->_agent, 'AOL '));
            if (count($verParts) > 1) {
                $verParts = explode(' ', $verParts[1]);
                $version = $verParts[0];
            }

            $this->setAol(true);
            $this->setAolVersion($version);

            return true;
        } else {
            $this->setAol(false);
            $this->setAolVersion('');

            return false;
        }
    }

     
    protected function checkPlatform()
    {
         
        if ($this->containString($this->_agent, array('Windows Phone', 'IEMobile'))) {  
            $this->setPlatform(self::PLATFORM_WINDOWS_PHONE);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'Windows CE') !== false) {  
            $this->setPlatform(self::PLATFORM_WINDOWS_CE);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'iPhone') !== false) {      
            $this->setPlatform(self::PLATFORM_IPHONE);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'iPad') !== false) {
            $this->setPlatform(self::PLATFORM_IPAD);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'iPod') !== false) {
            $this->setPlatform(self::PLATFORM_IPOD);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'Android') !== false) {
            $this->setPlatform(self::PLATFORM_ANDROID);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'Symbian') !== false) {
            $this->setPlatform(self::PLATFORM_SYMBIAN);
            $this->setMobile(true);
        } else if ($this->containString($this->_agent, array('BlackBerry', 'BB10', 'RIM Tablet OS'))) {
            $this->setPlatform(self::PLATFORM_BLACKBERRY);
            $this->setMobile(true);
        } else if (stripos($this->_agent, 'Nokia') !== false) {
            $this->setPlatform(self::PLATFORM_NOKIA);
            $this->setMobile(true);

         
        } else if (stripos($this->_agent, 'Windows') !== false) {
            $this->setPlatform(self::PLATFORM_WINDOWS);
        } else if (stripos($this->_agent, 'Macintosh') !== false) {
            $this->setPlatform(self::PLATFORM_MACINTOSH);
        } else if (stripos($this->_agent, 'Linux') !== false) {
            $this->setPlatform(self::PLATFORM_LINUX);
        } else if (stripos($this->_agent, 'FreeBSD') !== false) {
            $this->setPlatform(self::PLATFORM_FREEBSD);
        } else if (stripos($this->_agent, 'OpenBSD') !== false) {
            $this->setPlatform(self::PLATFORM_OPENBSD);
        } else if (stripos($this->_agent, 'NetBSD') !== false) {
            $this->setPlatform(self::PLATFORM_NETBSD);

         
        } else if (stripos($this->_agent, 'OpenSolaris') !== false) {
            $this->setPlatform(self::PLATFORM_OPENSOLARIS);
        } else if (stripos($this->_agent, 'OS/2') !== false) {
            $this->setPlatform(self::PLATFORM_OS2);
        } else if (stripos($this->_agent, 'BeOS') !== false) {
            $this->setPlatform(self::PLATFORM_BEOS);
        } else if (stripos($this->_agent, 'SunOS') !== false) {
            $this->setPlatform(self::PLATFORM_SUNOS);

         
        } else if (stripos($this->_agent, 'Win') !== false) {
            $this->setPlatform(self::PLATFORM_WINDOWS);
        } else if (stripos($this->_agent, 'Mac') !== false) {
            $this->setPlatform(self::PLATFORM_MACINTOSH);
        }

        //Check if it's a 64-bit platform
        if ($this->containString($this->_agent, array('WOW64', 'Win64', 'AMD64', 'x86_64', 'x86-64', 'ia64', 'IRIX64',
                'ppc64', 'sparc64', 'x64;', 'x64_64'))) {
            $this->set64bit(true);
        }

        $this->checkPlatformVersion();
    }

     
    protected function checkPlatformVersion()
    {
        $result = '';

        switch ($this->getPlatform()) {
            case self::PLATFORM_WINDOWS:
                if (preg_match('/Windows NT\s*([^\s;\)$]+)/i', $this->_agent, $foundVersion)) {
                    //Windows NT family
                    $result = 'NT ' . $foundVersion[1];
                } else {
                    //Windows 3.x / 9x family
                    //https://support.microsoft.com/en-us/kb/158238

                    if ($this->containString($this->_agent, array('Win 9x 4.90', 'Windows ME'))) {
                        $result = '4.90.3000'; //Windows Me version range from 4.90.3000 to 4.90.3000A
                    } else if (stripos($this->_agent, 'Windows 98') !== false) {
                        $result = '4.10'; //Windows 98 version range from 4.10.1998 to 4.10.2222B
                    } else if (stripos($this->_agent, 'Windows 95') !== false) {
                        $result = '4.00'; //Windows 95 version range from 4.00.950 to 4.03.1214
                    } else if (preg_match('/Windows 3\.([^\s;\)$]+)/i', $this->_agent, $foundVersion)) {
                        $result = '3.' . $foundVersion[1];
                    } else if (stripos($this->_agent, 'Win16') !== false) {
                        $result = '3.1';
                    }
                }
                break;

            case self::PLATFORM_MACINTOSH:
                if (preg_match('/Mac OS X\s*([^\s;\)$]+)/i', $this->_agent, $foundVersion)) {
                    $result = str_replace('_', '.', $foundVersion[1]);
                } else if (stripos($this->_agent, 'Mac OS X') !== false) {
                    $result = '10';
                }
                break;
            
            case self::PLATFORM_IPAD:
                if (preg_match('/OS\s*([^\s;\)$]+)/i', $this->_agent, $foundVersion)) {
                    $result = str_replace('_', '.', $foundVersion[1]);
                }
                break;
            
            case self::PLATFORM_ANDROID:
                if (preg_match('/Android\s+([^\s;$]+)/i', $this->_agent, $foundVersion)) {
                    $result = $foundVersion[1];
                }
                break;
        }

        if (trim($result) == '') {
            $result = self::PLATFORM_VERSION_UNKNOWN;
        }
        $this->setPlatformVersion($result);
    }

     
    protected function checkSimpleBrowserUA($uaNameToLookFor, $userAgent, $browserName, $isMobile = false, $isRobot = false, $separator = '/')
    {
        if (!is_array($uaNameToLookFor)) {
            $uaNameToLookFor = array($uaNameToLookFor);
        }

        foreach ($uaNameToLookFor as $currUANameToLookFor) {
            if (stripos($userAgent, $currUANameToLookFor) !== false) {
                //Many browsers don't use the standard "Browser/1.0" format, they uses "Browser 1.0;" instead
                if (stripos($userAgent, $currUANameToLookFor . $separator) === false) {
                    $userAgent = str_ireplace($currUANameToLookFor . ' ', $currUANameToLookFor . $separator, $this->_agent);
                }

                $version = '';
                $verParts = explode($separator, stristr($userAgent, $currUANameToLookFor));
                if (count($verParts) > 1) {
                    $verParts = explode(' ', $verParts[1]);
                    $version = $verParts[0];
                }

                $this->setBrowser($browserName);
                $this->setVersion($version);

                $this->setMobile($isMobile);
                $this->setRobot($isRobot);

                return true;
            }
        }

        return false;
    }

     
    protected function containString($haystack, $needle, $insensitive = true)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }

        foreach ($needle as $currNeedle) {
            if ($insensitive) {
                $found = stripos($haystack, $currNeedle) !== false;
            } else {
                $found = strpos($haystack, $currNeedle) !== false;
            }

            if ($found) {
                return true;
            }
        }

        return false;
    }

     
    protected function detect()
    {
        $this->checkBrowsers();
        $this->checkPlatform(); //Check the platform after the browser since some platforms can change the mobile value
        $this->checkForAol();
    }

     
    protected function cleanVersion($version)
    {
        //Clear anything that is in parentheses (and the parentheses themselves) - will clear started but unclosed ones too
        $cleanVer = preg_replace('/\([^)]+\)?/', '', $version);
        //Replace with a space any character which is NOT an alphanumeric, dot (.), hyphen (-), underscore (_) or space
        $cleanVer = preg_replace('/[^0-9.a-zA-Z_ -]/', ' ', $cleanVer);
        //Remove trailing and leading spaces
        $cleanVer = trim($cleanVer);
        //Remove double spaces if any
        while (strpos($cleanVer, '  ') !== false) {
            $cleanVer = str_replace('  ', ' ', $cleanVer);
        }

        return $cleanVer;
    }

     
    protected function macVerToStr($macVer)
    {
        //https://en.wikipedia.org/wiki/OS_X#Release_history

        if ($this->_platformVersion === '10') {
            return 'Mac OS X'; //Unspecified Mac OS X version
        } else if ($this->compareVersions($macVer, '10.12') >= 0 && $this->compareVersions($macVer, '10.13') < 0) {
            return 'macOS Sierra';
        } else if ($this->compareVersions($macVer, '10.11') >= 0 && $this->compareVersions($macVer, '10.12') < 0) {
            return 'OS X El Capitan';
        } else if ($this->compareVersions($macVer, '10.10') >= 0 && $this->compareVersions($macVer, '10.11') < 0) {
            return 'OS X Yosemite';
        } else if ($this->compareVersions($macVer, '10.9') >= 0 && $this->compareVersions($macVer, '10.10') < 0) {
            return 'OS X Mavericks';
        } else if ($this->compareVersions($macVer, '10.8') >= 0 && $this->compareVersions($macVer, '10.9') < 0) {
            return 'OS X Mountain Lion';
        } else if ($this->compareVersions($macVer, '10.7') >= 0 && $this->compareVersions($macVer, '10.8') < 0) {
            return 'Mac OS X Lion';
        } else if ($this->compareVersions($macVer, '10.6') >= 0 && $this->compareVersions($macVer, '10.7') < 0) {
            return 'Mac OS X Snow Leopard';
        } else if ($this->compareVersions($macVer, '10.5') >= 0 && $this->compareVersions($macVer, '10.6') < 0) {
            return 'Mac OS X Leopard';
        } else if ($this->compareVersions($macVer, '10.4') >= 0 && $this->compareVersions($macVer, '10.5') < 0) {
            return 'Mac OS X Tiger';
        } else if ($this->compareVersions($macVer, '10.3') >= 0 && $this->compareVersions($macVer, '10.4') < 0) {
            return 'Mac OS X Panther';
        } else if ($this->compareVersions($macVer, '10.2') >= 0 && $this->compareVersions($macVer, '10.3') < 0) {
            return 'Mac OS X Jaguar';
        } else if ($this->compareVersions($macVer, '10.1') >= 0 && $this->compareVersions($macVer, '10.2') < 0) {
            return 'Mac OS X Puma';
        } else if ($this->compareVersions($macVer, '10.0') >= 0 && $this->compareVersions($macVer, '10.1') < 0) {
            return 'Mac OS X Cheetah';
        } else {
            return self::PLATFORM_VERSION_UNKNOWN; //Unknown/unnamed Mac OS version
        }
    }
    
     
    protected function iOSVerToStr($iOSVer)
    {
        //https://en.wikipedia.org/wiki/IOS_version_history
        
        if ($this->_platformVersion <= '4') {
          return 'iOS';
        }
        else if ($this->_platformVersion >= '3')
        {
          return 'iPhone OS OS';
        }
        else {
          return PLATFORM_VERSION_UNKNOWN; //Unknown iOS version
        }
    }

     
    protected function parseInt($intStr)
    {
        return intval($intStr, 10);
    }

     
    protected function reset()
    {
        $this->_agent = '';
        $this->_aolVersion = '';
        $this->_browserName = self::BROWSER_UNKNOWN;
        $this->_compatibilityViewName = '';
        $this->_compatibilityViewVer = '';
        $this->_is64bit = false;
        $this->_isAol = false;
        $this->_isMobile = false;
        $this->_isRobot = false;
        $this->_platform = self::PLATFORM_UNKNOWN;
        $this->_platformVersion = self::PLATFORM_VERSION_UNKNOWN;
        $this->_version = self::VERSION_UNKNOWN;
    }

     
    protected function safariBuildToSafariVer($version)
    {
        $verParts = explode('.', $version);

        //We need a 3 parts version (version 2 will becomes 2.0.0)
        while (count($verParts) < 3) {
            $verParts[] = 0;
        }
        foreach ($verParts as $i => $currPart) {
            $verParts[$i] = $this->parseInt($currPart);
        }

        switch ($verParts[0]) {
            case 419: $result = '2.0.4';
                break;
            case 417: $result = '2.0.3';
                break;
            case 416: $result = '2.0.2';
                break;

            case 412:
                if ($verParts[1] >= 5) {
                    $result = '2.0.1';
                } else {
                    $result = '2.0';
                }
                break;

            case 312:
                if ($verParts[1] >= 5) {
                    $result = '1.3.2';
                } else {
                    if ($verParts[1] >= 3) {
                        $result = '1.3.1';
                    } else {
                        $result = '1.3';
                    }
                }
                break;

            case 125:
                if ($verParts[1] >= 11) {
                    $result = '1.2.4';
                } else {
                    if ($verParts[1] >= 9) {
                        $result = '1.2.3';
                    } else {
                        if ($verParts[1] >= 7) {
                            $result = '1.2.2';
                        } else {
                            $result = '1.2';
                        }
                    }
                }
                break;

            case 100:
                if ($verParts[1] >= 1) {
                    $result = '1.1.1';
                } else {
                    $result = '1.1';
                }
                break;

            case 85:
                if ($verParts[1] >= 8) {
                    $result = '1.0.3';
                } else {
                    if ($verParts[1] >= 7) {
                        $result = '1.0.2';
                    } else {
                        $result = '1.0';
                    }
                }
                break;

            case 73: $result = '0.9';
                break;
            case 51: $result = '0.8.1';
                break;
            case 48: $result = '0.8';
                break;

            default: $result = '';
        }

        return $result;
    }

     
    protected function set64bit($is64bit)
    {
        $this->_is64bit = $is64bit == true;
    }

     
    protected function setAol($isAol)
    {
        $this->_isAol = $isAol == true;
    }

     
    protected function setAolVersion($version)
    {
        $cleanVer = $this->cleanVersion($version);

        $this->_aolVersion = $cleanVer;
    }

     
    protected function setBrowser($browserName)
    {
        $this->_browserName = $browserName;
    }

     
    protected function setMobile($isMobile = true)
    {
        $this->_isMobile = $isMobile == true;
    }

     
    protected function setPlatform($platform)
    {
        $this->_platform = $platform;
    }

     
    protected function setPlatformVersion($platformVer)
    {
        $this->_platformVersion = $platformVer;
    }

     
    protected function setRobot($isRobot = true)
    {
        $this->_isRobot = $isRobot == true;
    }

     
    protected function setVersion($version)
    {
        $cleanVer = $this->cleanVersion($version);

        if ($cleanVer == '') {
            $this->_version = self::VERSION_UNKNOWN;
        } else {
            $this->_version = $cleanVer;
        }
    }

     
    protected function webKitBuildToSafariVer($version)
    {
        $verParts = explode('.', $version);

        //We need a 3 parts version (version 2 will becomes 2.0.0)
        while (count($verParts) < 3) {
            $verParts[] = 0;
        }
        foreach ($verParts as $i => $currPart) {
            $verParts[$i] = $this->parseInt($currPart);
        }

        switch ($verParts[0]) {
            case 419: $result = '2.0.4';
                break;

            case 418:
                if ($verParts[1] >= 8) {
                    $result = '2.0.4';
                } else {
                    $result = '2.0.3';
                }
                break;

            case 417: $result = '2.0.3';
                break;

            case 416: $result = '2.0.2';
                break;

            case 412:
                if ($verParts[1] >= 7) {
                    $result = '2.0.1';
                } else {
                    $result = '2.0';
                }
                break;

            case 312:
                if ($verParts[1] >= 8) {
                    $result = '1.3.2';
                } else {
                    if ($verParts[1] >= 5) {
                        $result = '1.3.1';
                    } else {
                        $result = '1.3';
                    }
                }
                break;

            case 125:
                if ($this->compareVersions('5.4', $verParts[1] . '.' . $verParts[2]) == -1) {
                    $result = '1.2.4'; //125.5.5+
                } else {
                    if ($verParts[1] >= 4) {
                        $result = '1.2.3';
                    } else {
                        if ($verParts[1] >= 2) {
                            $result = '1.2.2';
                        } else {
                            $result = '1.2';
                        }
                    }
                }
                break;

            //WebKit 100 can be either Safari 1.1 (Safari build 100) or 1.1.1 (Safari build 100.1)
            //for this reason, check the Safari build before the WebKit build.
            case 100: $result = '1.1.1';
                break;

            case 85:
                if ($verParts[1] >= 8) {
                    $result = '1.0.3';
                } else {
                    if ($verParts[1] >= 7) {
                        //WebKit 85.7 can be either Safari 1.0 (Safari build 85.5) or 1.0.2 (Safari build 85.7)
                        //for this reason, check the Safari build before the WebKit build.
                        $result = '1.0.2';
                    } else {
                        $result = '1.0';
                    }
                }
                break;

            case 73: $result = '0.9';
                break;
            case 51: $result = '0.8.1';
                break;
            case 48: $result = '0.8';
                break;

            default: $result = '';
        }

        return $result;
    }

     
    protected function windowsNTVerToStr($winVer, $returnServerFlavor = false)
    {
        //https://en.wikipedia.org/wiki/List_of_Microsoft_Windows_versions

        $cleanWinVer = explode('.', $winVer);
        while (count($cleanWinVer) > 2) {
            array_pop($cleanWinVer);
        }
        $cleanWinVer = implode('.', $cleanWinVer);

        if ($this->compareVersions($cleanWinVer, '11') >= 0) {
            //Future versions of Windows
            return self::PLATFORM_WINDOWS . ' ' . $winVer;
        } else if ($this->compareVersions($cleanWinVer, '10') >= 0) {
            //Current version of Windows
            return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2016') : (self::PLATFORM_WINDOWS . ' 10');
        } else if ($this->compareVersions($cleanWinVer, '7') < 0) {
            if ($this->compareVersions($cleanWinVer, '6.3') == 0) {
                return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2012 R2') : (self::PLATFORM_WINDOWS . ' 8.1');
            } else if ($this->compareVersions($cleanWinVer, '6.2') == 0) {
                return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2012') : (self::PLATFORM_WINDOWS . ' 8');
            } else if ($this->compareVersions($cleanWinVer, '6.1') == 0) {
                return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2008 R2') : (self::PLATFORM_WINDOWS . ' 7');
            } else if ($this->compareVersions($cleanWinVer, '6') == 0) {
                return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2008') : (self::PLATFORM_WINDOWS . ' Vista');
            } else if ($this->compareVersions($cleanWinVer, '5.2') == 0) {
                return $returnServerFlavor ? (self::PLATFORM_WINDOWS . ' Server 2003 / ' . self::PLATFORM_WINDOWS . ' Server 2003 R2') : (self::PLATFORM_WINDOWS . ' XP x64 Edition');
            } else if ($this->compareVersions($cleanWinVer, '5.1') == 0) {
                return self::PLATFORM_WINDOWS . ' XP';
            } else if ($this->compareVersions($cleanWinVer, '5') == 0) {
                return self::PLATFORM_WINDOWS . ' 2000';
            } else if ($this->compareVersions($cleanWinVer, '5') < 0 && $this->compareVersions($cleanWinVer, '3') >= 0) {
                return self::PLATFORM_WINDOWS . ' NT ' . $winVer;
            }
        }

        return self::PLATFORM_VERSION_UNKNOWN; //Invalid Windows NT version
    }

     
    protected function windowsVerToStr($winVer)
    {
        //https://support.microsoft.com/en-us/kb/158238

        if ($this->compareVersions($winVer, '4.90') >= 0 && $this->compareVersions($winVer, '4.91') < 0) {
            return self::PLATFORM_WINDOWS . ' Me'; //Normally range from 4.90.3000 to 4.90.3000A
        } else if ($this->compareVersions($winVer, '4.10') >= 0 && $this->compareVersions($winVer, '4.11') < 0) {
            return self::PLATFORM_WINDOWS . ' 98'; //Normally range from 4.10.1998 to 4.10.2222B
        } else if ($this->compareVersions($winVer, '4') >= 0 && $this->compareVersions($winVer, '4.04') < 0) {
            return self::PLATFORM_WINDOWS . ' 95'; //Normally range from 4.00.950 to 4.03.1214
        } else if ($this->compareVersions($winVer, '3.1') == 0 || $this->compareVersions($winVer, '3.11') == 0) {
            return self::PLATFORM_WINDOWS . ' ' . $winVer;
        } else if ($this->compareVersions($winVer, '3.10') == 0) {
            return self::PLATFORM_WINDOWS . ' 3.1';
        } else {
            return self::PLATFORM_VERSION_UNKNOWN; //Invalid Windows version
        }
    }
}
