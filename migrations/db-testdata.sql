USE `cmgdemo`;

/* ============================= CMS Gears Forum ============================================== */

--
-- Dumping data for table `cmg_option`
--

INSERT INTO `cmg_option` VALUES 
	(3001,1,'Forum','3001');

--
-- Dumping data for table `cmg_role`
--

INSERT INTO `cmg_role` VALUES 
	(3001,'Forum Manager','The role Forum Manager is limited to manage forums from admin.','/',0);

--
-- Dumping data for table `cmg_permission`
--

INSERT INTO `cmg_permission` VALUES 
	(3001,'forum','The permission cms is to manage cms module from admin.');

--
-- Dumping data for table `cmg_role_permission`
--

INSERT INTO `cmg_role_permission` VALUES 
	(1,3001),
	(2,3001),
	(3001,3001);
	
--
-- Dumping data for table `cmg_user`
--

INSERT INTO `cmg_user` VALUES 
	(3001,3001,NULL,NULL,NULL,10,'demoforummanager@cmsgears.org','demoforummanager','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL);