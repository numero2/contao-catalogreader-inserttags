<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  2011 numero2 - Agentur für Internetdienstleistungen
 * @author     numero2 (http://www.numero2.de)
 * @package    Registration
 * @license    LGPL
 */
 
class ModuleCatalogReaderInsertTags extends Controller {
	
	protected function replaceInsertTags($strBuffer, $blnCache=false) {

	
		global $objPage;
		$this->import('Database');
	
        $aParams = explode('::', $strBuffer);
 
        switch( $aParams[0] ) {
        
            case 'catalog_reader' :

				// find catalog
				$objCatalogID = $this->Database->prepare("SELECT catalog FROM tl_module WHERE jumpTo = ? AND type = 'catalogreader'")->execute($objPage->id);
				
				if( !$objCatalogID )
					return false;
				
				$catalogID = $objCatalogID->catalog;
					
				// find table with catalog data
				$objCatalogType = $this->Database->prepare("SELECT * FROM tl_catalog_types WHERE id = ?")->execute($objCatalogID->catalog);
					
				if( !$objCatalogType )
					return false;
					
				$value = $this->Input->get('items');
				$strAlias = $objCatalogType->aliasField ? $objCatalogType->aliasField : (is_numeric($value) ? "id" : '');
				
				// select current entry
				$objCatalogData = $this->Database->prepare("SELECT * FROM ".$objCatalogType->tableName." WHERE ".$strAlias." = ?")->execute($value);
				
				if( !$objCatalogData || !$objCatalogData->$aParams[1] )
					return false;
					
				return $objCatalogData->$aParams[1];

            break;
            
            // not our insert tag?
            default :
                return false;
            break;
        }

        return false;
    
    }
}

?>