<?php

namespace Concrete\Package\AttributeSimpleFile;

use Concrete\Core\Backup\ContentImporter;
use Package;

use Concrete\Core\Asset\AssetList;

class Controller extends Package
{

    protected $pkgHandle = 'attribute_simple_file';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '1.0.1';

    public function getPackageName()
    {
        return t('Simple file attribute');
    }

    public function getPackageDescription()
    {
        return t('Installs a simple file attribute that doesn\'t add files to the file manager');
    }

    protected function installXmlContent()
    {
        $pkg = Package::getByHandle($this->pkgHandle);

        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/install.xml');
    }

    public function install()
    {
        $pkg = parent::install();

        $this->installXmlContent();
    }

    public function upgrade()
    {
        parent::upgrade();

        $this->installXmlContent();
    }

    protected function registerAssets()
    {
        $al = AssetList::getInstance();
        $pkg = Package::getByHandle($this->pkgHandle);

        $al->register('javascript', 'attribute-simple-file', 'js/attribute-simple-file.js',
            array('version' => '1.0.0', 'minify' => true, 'combine' => true), $pkg
        );
        $al->registerGroup('attribute-simple-file', array(
            array('javascript', 'attribute-simple-file'),
        ));
    }

    public function on_start() {
        $this->registerAssets();
    }
}
