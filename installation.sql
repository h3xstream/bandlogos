CREATE TABLE `lastfm_images_cache_blob` (
  `idimage` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(45) NOT NULL,
  `nb_artists` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `color` varchar(10) NOT NULL,
  `layout` varchar(10) NOT NULL,
  `image` mediumblob NOT NULL,
  PRIMARY KEY  (`idimage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `lastfm_artists` (
  `idartist` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `path_logo` varchar(200) NOT NULL,
  `lastfm_uid` varchar(36) NOT NULL,
  `date_added` int(10) NOT NULL,
  PRIMARY KEY  (`idartist`)
) ENGINE=InnoDB AUTO_INCREMENT=1601 DEFAULT CHARSET=latin1;