<?php

namespace Concrete\Package\AttributeSimpleFile\Attribute\AttributeSimpleFile;

use Database;
use Concrete\Core\Attribute\Controller as AttributeTypeController;

class Controller extends AttributeTypeController
{
    public $helpers = ['form'];

    public function deleteKey()
    {
        $db = Database::connection();
        $db->delete('atSimpleFile', ['akID' => $this->getAttributeKey()->getAttributeKeyID()]);
    }

    public function deleteValue()
    {
        $db = Database::connection();
        $db->executeQuery('DELETE from atSimpleFile where avID = ?', array($this->getAttributeValueID()));
    }
    /**
     * Returns the value entered in the HTML editor
     * @return string
     */
    public function getValue()
    {
        $db = Database::connection();
        $avID = $this->getAttributeValueID();

        if ($avID) {
            $row = $db->fetchAssoc('SELECT fileName, uniqueKey FROM atSimpleFile WHERE avID = ?', [$avID]);
            return '<a target="_blank" href="' . DIR_REL . '/application/files/simple_file_attribute/' . $row['uniqueKey'] . '-' . $row['fileName'] . '">' . $row['fileName'] . '</a>';
        }
    }

    /**
     * Shows the attribute configuration form
     */
    public function type_form()
    {
    }

    /**
     * Saves the attribute configuration
     * @param $data
     */
    public function saveKey($data)
    {
    }

    /**
     * Shows the form to enter the value
     */
    public function form()
    {
        $this->requireAsset('attribute-simple-file');
        echo $this->getValue();
    }

    /**
     * Called when we're searching using an attribute.
     * @param $list
     */
    public function searchForm($list)
    {

    }

    /**
     * Called when we're saving the attribute from the frontend.
     * @param $data
     */
    public function saveForm($data)
    {
        $ak = $this->getAttributeKey();
        $akID = $ak->getAttributeKeyID();

        $tmpName = $_FILES['akID']['tmp_name'][$akID]['value'];
        $name = $_FILES['akID']['name'][$akID]['value'];
        if (!empty($tmpName) && is_uploaded_file($tmpName)) {
            $directory = DIR_BASE . '/application/files/simple_file_attribute';
            if (!file_exists($directory)) {
                mkdir($directory);
            }
            $uniqueKey = uniqid();
            $filename = $uniqueKey . '-' . $name;

            if (move_uploaded_file($tmpName, $directory . '/' . $filename)) {
                $db = Database::connection();
                $db->Replace('atSimpleFile', array('avID' => $this->getAttributeValueID(), 'fileName' => $name, 'uniqueKey' => $uniqueKey), 'avID', true);
            }
        }
    }

    /**
     * Called when the attribute is edited in the composer.
     */
    public function composer()
    {
        $this->form();
    }

}
