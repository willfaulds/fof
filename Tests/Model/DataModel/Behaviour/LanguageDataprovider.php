<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

class LanguageDataprovider
{
    public static function getTestOnBeforeBuildQuery()
    {
        $data[] = array(
            'input' => array(
                'langField' => null,
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => false
                )
            ),
            'check' => array(
                'case'      => "Model hasn't a language field",
                'blacklist' => 1,
                'where'     => array()
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(),
                'mock' => array(
                    'admin'        => false,
                    'removePrefix' => false,
                    'langFilter'   => null
                )
            ),
            'check' => array(
                'case'      => "Application doesn't have the language filter method",
                'blacklist' => 1,
                'where'     => array()
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(),
                'mock' => array(
                    'admin'        => false,
                    'removePrefix' => false,
                    'langFilter'   => false
                )
            ),
            'check' => array(
                'case'      => "Application has the language filter method, but return false",
                'blacklist' => 1,
                'where'     => array()
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(),
                'mock' => array(
                    'admin'        => false,
                    'removePrefix' => true,
                    'langFilter'   => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option set to remove the prefix",
                'blacklist' => 1,
                'where'     => array("`language` IN('*', 'en-GB')")
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(),
                'mock' => array(
                    'admin'        => true,
                    'removePrefix' => true,
                    'langFilter'   => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option set to remove the prefix, we're in backend",
                'blacklist' => 0,
                'where'     => array("`language` IN('*', 'en-GB')")
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(),
                'mock' => array(
                    'admin'        => false,
                    'removePrefix' => false,
                    'langFilter'   => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option not set to remove the prefix, language is not set inside app input",
                'blacklist' => 1,
                'where'     => array("`language` IN('*')")
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'language',
                'input' => array(
                    'language' => 'it-IT'
                ),
                'mock' => array(
                    'admin'        => false,
                    'removePrefix' => false,
                    'langFilter'   => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option not set to remove the prefix, language is set inside app input",
                'blacklist' => 1,
                'where'     => array("`language` IN('*', 'it-IT')")
            )
        );

        return $data;
    }

    public static function getTestOnAfterLoad()
    {
        $data[] = array(
            'input' => array(
                'langField' => null,
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => false
                )
            ),
            'check' => array(
                'case'      => "Model hasn't a language field",
                'reset'     => 0
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'it-IT',
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => null
                )
            ),
            'check' => array(
                'case'      => "Application doens't have a language filter method",
                'reset'     => 0
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'it-IT',
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => false
                )
            ),
            'check' => array(
                'case'      => "Application has a language filter method and returns false",
                'reset'     => 0
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'it-IT',
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => true,
                    'langFilter' => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option set to remove the prefix, model language is not the same of the site",
                'reset'     => 1
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'en-GB',
                'input' => array(),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => true,
                    'langFilter' => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option set to remove the prefix, model language is the same of the site",
                'reset'     => 0
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'it-IT',
                'input' => array(
                    'language' => 'en-GB'
                ),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option not set to remove the prefix, language is set inside app input, model language is not the same of the site",
                'reset'     => 1
            )
        );

        $data[] = array(
            'input' => array(
                'langField' => 'en-GB',
                'input' => array(
                    'language' => 'en-GB'
                ),
                'mock' => array(
                    'admin' => false,
                    'removePrefix' => false,
                    'langFilter' => true
                )
            ),
            'check' => array(
                'case'      => "Plugin option not set to remove the prefix, language is set inside app input, model language is the same of the site",
                'reset'     => 0
            )
        );

        return $data;
    }
}
