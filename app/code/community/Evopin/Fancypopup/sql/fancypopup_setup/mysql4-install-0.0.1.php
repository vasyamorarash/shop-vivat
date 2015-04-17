<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('evopin_fancypopup')};
CREATE TABLE {$this->getTable('evopin_fancypopup')} (
  `fancypopup_id` int(11) unsigned NOT NULL auto_increment,
  `popup_name` varchar(30) NOT NULL default '',
  `image_link` varchar(255) NOT NULL default '',
  `width_image` smallint(4) NOT NULL default '0',
  `height_image` smallint(4) NOT NULL default '0',    
  `url_link` varchar(255) NOT NULL default '',  
  `is_active` tinyint(1) NOT NULL default '1',
  `from_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_date` datetime NOT NULL default '0000-00-00 00:00:00',    
  `css_style` varchar(64) NOT NULL default '',
  `js_style` varchar(64) NOT NULL default '',
  `delay_start` tinyint(3) NOT NULL default '0',
  `delay_close` tinyint(3) NOT NULL default '0',
  `priority` tinyint(4) NOT NULL default '0',      
  `opacity` decimal(3,2) NOT NULL default '0.4',     
  PRIMARY KEY (`fancypopup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('evopin_fancypopup_store')}`;
CREATE TABLE `{$this->getTable('evopin_fancypopup_store')}` (
  `fancy_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`fancy_id`,`store_id`),
  CONSTRAINT `FK_FANCYPOPUP_STORE_FANCYPOPUP` FOREIGN KEY (`fancy_id`) REFERENCES `{$this->getTable('evopin_fancypopup')}` (`fancypopup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_FANCYPOPUP_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store for fancy popup';


    ");
    

$installer->endSetup(); 