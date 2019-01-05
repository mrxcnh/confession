CREATE DATABASE  IF NOT EXISTS `confession` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `confession`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: confession
-- ------------------------------------------------------
-- Server version	5.7.22-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'ALPhaHoai','alphahoai@gmail.com','5921139f3e13a3b313019d071c5e8898','https://cdn1.iconfinder.com/data/icons/ninja-things-1/1772/ninja-simple-512.png','2018-09-28 17:37:09','2018-10-04 09:50:43'),(2,'Admin 2','admin@gmail.com','d8893abb802b2eb3eda79f72caae14ca','https://cdn4.iconfinder.com/data/icons/avatars-21/512/avatar-circle-human-male-3-512.png','2018-10-05 15:18:03','2018-10-05 09:10:32');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `like` int(10) unsigned NOT NULL DEFAULT '0',
  `dislike` int(10) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (75,'The first confesstion. To confess, go to this link: http://anigoo.net/confession',1,2,0,0,'2018-10-03 14:58:39','2018-10-03 07:58:39'),(76,'http://anigoo.net/confession',1,2,0,0,'2018-10-03 15:21:40','2018-10-03 08:21:40'),(77,'xin chao javascript',1,2,0,1,'2018-10-03 15:27:48','2018-10-03 08:29:15'),(78,'http://confession.com/confession/5',5,2,0,0,'2018-10-03 15:46:43','2018-10-03 08:46:43'),(79,'https://www.facebook.com/messages/t/1943309185727846',5,2,0,0,'2018-10-03 15:46:54','2018-10-03 08:46:54'),(80,'Không chia sẻ con số chính xác nhưng ông Trần Đình Toản - Phó tổng giám đốc OSB, đơn vị đối tác của Alibaba để kết nối với nhà sản xuất tại Việt Nam cho hay, đã có hàng nghìn doanh nghiệp Việt xuất khẩu qua nền tảng B2B của \"đại gia\" thương mại điện tử Trung Quốc trong gần một thập niên qua.\n\nHoạt động của Amazon và Alibaba tại Việt Nam diễn ra trong bối cảnh xuất khẩu ngày càng đóng vai trò quan trọng đối với nền kinh tế đất',5,2,0,0,'2018-10-03 15:47:07','2018-10-03 08:47:07'),(81,'đến quan trọng của',5,2,0,0,'2018-10-03 15:47:19','2018-10-03 08:47:19'),(82,'đến quan trọng của \n đến quan trọng củafff',5,2,0,0,'2018-10-03 15:47:23','2018-10-03 08:47:23'),(83,'đến quan trọng của fkdf',5,2,0,0,'2018-10-03 15:47:25','2018-10-03 08:47:25'),(84,'đến quan trọng của fsfls',5,2,0,0,'2018-10-03 15:47:27','2018-10-03 08:47:27'),(85,'đến quan trọng củafdfdklf\n đến quan trọng của',5,2,0,0,'2018-10-03 15:47:30','2018-10-03 08:47:30'),(86,'gsrfgdfgdf dgdfgf',5,2,0,0,'2018-10-03 15:47:33','2018-10-03 08:47:33'),(87,'http://localhost/phpmyadmin/tbl_change.php?db=confession&table=comment',1,1,0,0,'2018-10-03 15:48:39','2018-10-03 08:48:39'),(88,'sdfksldm',1,2,0,0,'2018-10-03 15:48:58','2018-10-03 08:48:58'),(89,'gdgdkfgldfmdglf',1,2,0,0,'2018-10-03 15:49:00','2018-10-03 08:49:00'),(90,'dgkldfmgdlkfgdfg',1,2,0,0,'2018-10-03 15:49:02','2018-10-03 08:49:02'),(91,'http://localhost/phpmyadmin/tbl_change.php?db=confession&table=comment',1,2,0,0,'2018-10-03 15:49:04','2018-10-03 08:49:04'),(92,'http://localhost/phpmyadmin/tbl_change.php?db=confession&table=comment fsdf',1,2,0,0,'2018-10-03 15:49:07','2018-10-03 08:49:07'),(93,'http://www.aseancu.org/lms/lecture/toc/doTocList.a170y.acu?lectureSeqno=979# fdf',1,2,0,0,'2018-10-03 15:49:25','2018-10-03 08:49:25'),(94,'http://www.aseancu.org/lms/lecture/toc/doTocList.a170y.acu?lectureSeqno=979#',1,2,0,0,'2018-10-03 15:49:36','2018-10-03 08:49:36'),(95,'sfsdfsdf',1,2,0,0,'2018-10-03 15:58:10','2018-10-03 08:58:10'),(96,'fsfsdfsdf',4,2,0,0,'2018-10-04 12:34:20','2018-10-04 05:34:20'),(97,'dgdfgdfgdfgfd',2,2,0,0,'2018-10-04 12:34:27','2018-10-04 05:34:27'),(98,'n ngành của tôi. Tôi bắt đầu vỡ mộng và tôi vẫn cố theo đuổi nó tới tận bây giờ chỉ vì tôi nghĩ đó là trách nhiệm, rồi tự nhủ mình \'khó khăn tí đã nản\' rồi tôi lại tự đứng nên và đi tiếp. Nhưng càng ngày mọi thứ càng rối tung lên như 1 thứ bòng bong, tôi cảm thấy áp lực và bất lực, các môn học ở trên lớp thì khó, tôi vừa học vừa',2,2,0,0,'2018-10-04 12:34:46','2018-10-04 05:34:46'),(99,'n ngành của tôi. Tôi bắt đầu vỡ mộng và tôi vẫn cố theo đuổi nó tới tận bây giờ chỉ vì tôi nghĩ đó là trách nhiệm, rồi tự nhủ mình \'khó khăn tí đã nản\' rồi tôi lại tự đứng nên và đi tiếp. Nhưng càng ngày mọi thứ càng rối tung lên như 1 thứ bòng bong, tôi cảm thấy áp lực và bất lực, các môn học ở trên lớp thì khó, tôi vừa học vừa  gdfgd',2,2,0,0,'2018-10-04 12:34:55','2018-10-04 05:34:55');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_add_comment_count

 BEFORE INSERT ON comment

 FOR EACH ROW

BEGIN

 UPDATE post set cmt = cmt + 1 where id = NEW.post_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_sub_comment_count

 BEFORE DELETE ON comment

 FOR EACH ROW

BEGIN

 UPDATE post set cmt = cmt - 1 where id = OLD.post_id;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `approval` enum('yes','no','not yet') NOT NULL DEFAULT 'not yet',
  `approval_by` mediumint(8) unsigned DEFAULT NULL,
  `approval_time` datetime DEFAULT NULL,
  `view` int(10) unsigned NOT NULL DEFAULT '0',
  `like` int(10) unsigned NOT NULL DEFAULT '0',
  `dislike` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt` int(10) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `approval_by` (`approval_by`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`approval_by`) REFERENCES `admin` (`id`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'The first confesstion. To confess, go to this link: http://anigoo.net/confession',1,'yes',1,'2018-09-30 00:10:42',16,0,2,12,'2018-09-28 17:38:33','2018-10-05 07:58:24'),(2,'Tôi đang là sinh viên năm ba, chỉ còn 1 năm nữa là ra trường . Chưa bao giờ tôi cảm thấy mâu lung như lúc này. Có những tâm sự chẳng thể giãi bày. Tôi thấy mệt mỏi . Giờ này năm ngoái tôi đang phân vân nên xin vào lab nào, xin theo thầy cô nào. Tôi ghét nhất phải lựa chọn nhưng có những lúc phải như thế bởi tôi chẳng biết mình thích gì, đam mê cái gì, ngay cả việc chọn trường và bây giờ là theo đuổi 1 chuyên ngành. Tôi chọn cũng chủ theo cảm tính chứ chưa thực sự hiểu rõ về nó, và hồi đó thì chỉ nghĩ như \'ếch ngồi đáy giếng thôi\' . Khi vào rồi thì tôi thấy chán vì phải làm những công việc lặp đi lặp lại chán ngắt và có vẻ chả liên quan mấy đến cái chuyên ngành của tôi. Tôi bắt đầu vỡ mộng và tôi vẫn cố theo đuổi nó tới tận bây giờ chỉ vì tôi nghĩ đó là trách nhiệm, rồi tự nhủ mình \'khó khăn tí đã nản\' rồi tôi lại tự đứng nên và đi tiếp. Nhưng càng ngày mọi thứ càng rối tung lên như 1 thứ bòng bong, tôi cảm thấy áp lực và bất lực, các môn học ở trên lớp thì khó, tôi vừa học vừa phải bố trí thời gian làm thí nghiệm. Từ ngày lên lab thì việc ko đk nghỉ t7, CN là điều bình thường với tôi. Bởi lúc đó tôi chỉ mong nhanh chóng kết quả để không phụ lòng thầy và hơn nữa là cho khóa luận của tôi, 1 năm miệt mài như thế nhưng tôi chả thu được kết quả gì, tôi băt đầu nản dần đều. Tôi thấy chán với con đường nghiên cứu, phải chăng tôi không có duyên. Có thể nhiều người nghĩ tôi đang ngụy biện. Nhưng mn có hiểu cảm giác của tôi?Trước đây tôi từng nghĩ sẽ xin học bổng sau khi ra trường nhưng giờ tôi mất dần niềm tin vào bản thân mình, tôi không còn hi vọng, mơ ước như trước nữa. Mọi thứ như tan biến dần trong suy nghĩ của tôi. Ra trường biết xin về đâu , làm gì? Buồn\r\nNguồn: BK Confessions',1,'yes',1,'2018-09-30 15:13:05',0,0,2,3,'2018-09-29 22:03:44','2018-10-05 08:10:19'),(3,'E học (HPU2) đang thích 1 a BK năm 3. Giới thiệu đôi chút thì e cũng cao ráo vòng nào cũng ok trừ vòng 1 hơi lép -_- , ưa nhìn, vui tính, hòa đồng, thân thiện, .... tóm lại là đáng yêu hihi. \r\nChuyện là e thích ông kia, cũng ib nhắn tin các thứ các kiểu ( toàn e nt trước) ông ý rep cũng bt đôi hôm trắc bận chơi điện tử như đấm vào tai .Em cũng thả thính, thả bả ,thả tất cả cái gì có thể thả , bật tất cả các loại đèn xanh tím mà ông cứ bơ bơ ra rồi cứ ngơ ngơ như bò đeo tai nghe ấy... hờn. Từ ngày biết ông ý e như kiểu vừa chơi câu cá vừa ăn bơ giữa ngã tư đèn giao thông ấy :)) .\r\n\r\nHôm trước e có đi biển ,cũng 4 dây các kiểu xong chụp con ảnh như minh tinh đùa chứ như ảnh mạng hihi tiếp là ngồi 2 tiếng đồng hồ chèn quả chữ \" LÀM NGƯỜI YÊU EM NHÉ xuống dòng Ý ANH SAO\". Gửi xong cho ô ý mà e còn méo dám cầm đt , vứt ở khách sạn rồi e đi chơi vs 1 tâm hồn vui như con điên ahihi. Về hí hửng xem ông ý rep tnao . Các bác ạ chỉ muốn túm tóc tạt tai tát tới tấp, vặn cổ từ đằng trc ra đằng sau  -_-. vỏn vẹn có \"hai chấm ngoặc ngoặc\" -_- điên lắm mà không làm gì được hic hic . Còn cả con ảnh xinh lung linh e ngồi đó và viết tên ông ý trên cát ông ý cũng k care luôn. Đùa chứ bố e e còn k viết đi viết tên ông ý thế mà không care được câu -_- haizzzz\r\n\r\nĐùa chứ từ mẫu giáo đến giờ cứ ai thích em thì em đâm ra ghét nó còn em cứ thích ai thì nó không quan tâm em luôn, hic hic tôi khổ quá mà. Muốn làm dâu BK mà khó quá hic hic.\r\nCuối tuần lảm nhảm tý ahihi\r\n\r\nNguồn: BKConfessions',1,'yes',2,'2018-10-02 14:54:09',0,1,0,0,'2018-09-29 22:07:16','2018-10-05 08:20:43'),(4,'#15847: Đừng bao giờ NÁT rượu...\r\nChuyện ngắn gọn như này, em với thằng bạn cùng Crush đều năm nhất, nhà Crush ở Hà Nội luôn ạ, em với Crush học chung lớp tiếng Anh xong chơi với nhau, thấy cũng hiểu nhau đâm ra cũng có thích nhau chun chút nhưng chưa tỏ tình...Và Crush em cũng biết điều đó. Tính em thì ngại lắm...ko dám tỏ tình mà cũng chưa tỏ tình ai bao giờ. Tự dưng tuần vừa rồi, Crush kêu em vs thằng bạn (Cũng chơi với nhau) với mấy đứa nữa qua nhà Crush ăn lẩu xong uống rượu liên hoan. Thằng bạn em mới bảo là...\r\n- Thế thì uống nhiều vào, xong rồi giả vờ say tỏ tình. Đồng ý thì ngon mà từ chối thì bảo rượu nói.\r\nCuối cùng uống hơi quá thật, em với thằng bạn em hơi nát...em nằm lăn ra đó, thằng bạn em cũng nát nát. Nó đứng dậy...đi đái, xong em hỏi \r\n- Đi đâu thế?\r\n- Đi đái.\r\n- Tao đi với, mót đái quá.\r\n- Mày đi đc ko tao vác.\r\nEm thề là lúc đó em éo biết gì luôn, nói đúng theo phản xạ vì mót quá rồi...thế là thằng bạn em nó vác em đi, em cứ ngửa cổ lên trời thôi. Xong nó còn tụt quần cho em rồi bảo...\r\n- Đấy đái đi.\r\nVà các anh chị biết gì ko ạ :( đ .m nó dắt em ra cái máy giặt nhà Crush để đái, cái máy giặt lồng ngang ấy ạ, còn khoe vừa đái ở đó, mà cay nữa là đang đái thì crush nhìn thấy rồi hét toáng lên...sau đó chuyện gì xảy ra em cũng éo nhớ nữa...chỉ nhớ đến đấy thôi, lúc dậy thì thấy mình ở phòng rồi. Thằng bạn nó kể lại cho em là sau khi crush hét lên thì nó nhanh chóng đóng quần và ship em về nhà = cách gọi taxi, crush ở lại dọn dẹp ko bị bố mẹ mắng...\r\nĐến giờ thì em vs Crush ko nói chuyện với nhau lời nào mặc dù em có xin lỗi và hỏi cái máy có hỏng ko, em xin tiền sửa hoặc đền cái khác...crush bơ em luôn. Thậm chí em còn ko thấy tên Crush nữa, \"người dùng facebook\" ấy ạ. \r\nEm phải làm sao :( cứu em với :(\r\n\r\nNguồn: <a href=\"https://www.facebook.com/neuconfessions\">NEU Confessions</a>',1,'yes',2,'2018-10-02 14:54:12',0,1,0,1,'2018-09-29 22:29:01','2018-10-05 08:20:51'),(5,'mplate engines are used for using PHP in documents that contain mostly HTML. In fact, PHP\'s original purpose was to be a templating language. That\'s why with PHP you can use things like short tags to echo variables (e.g. <?=$someVariable?>).\r\n\r\nThere are other template engines (such as Smarty, Twig, etc.) that make the syntax even more concise (e.g. {{someVariable}}).\r\n\r\nThe primary benefit of using a template engine is keeping the design (Presentation Logic) separate from the coding (Business Logic). It also makes the code cleaner and easier to maintain in the long run.\r\n\r\nIf you have any more questions feel free to le',2,'not yet',NULL,NULL,0,0,1,9,'2018-10-02 15:12:22','2018-10-05 08:21:04'),(6,'Xin chao viet nam',2,'no',2,NULL,0,1,0,0,'2018-10-02 15:15:54','2018-10-05 08:21:22'),(7,'Học sinh Sài Gòn được nghỉ Tết 16 ngày\r\nHơn 1,6 triệu học sinh được nghỉ Tết Kỷ Hợi từ 23 tháng Chạp, sớm hơn mọi năm và đi học ngày Mùng 7 tháng Giêng.\r\nNgày 4/10, Sở Giáo dục và Đào tạo TP HCM chốt lịch nghỉ Tết Kỷ Hợi đối với học sinh. Theo kế hoạch của UBND thành phố, các trường phổ thông được nghỉ từ ngày 28/1/2019 (nhằm ngày 23 tháng Chạp) đến hết ngày 10/2/2019 (Mùng 6 tháng Giêng).\r\n\r\nDo trước kỳ nghỉ là hai ngày cuối tuần nên kỳ nghỉ Tết kéo dài 16 ngày, bằng với những năm trước.\r\n\r\nLịch kiểm tra học kỳ một dự kiến từ ngày 10 đến 22/12, học kỳ hai từ ngày 15/4 đến 11/5/2019.',3,'not yet',NULL,NULL,0,0,0,0,'2018-10-04 17:12:31','2018-10-05 08:09:37');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_update_approval_time
BEFORE   UPDATE ON post FOR EACH ROW
BEGIN
   if (NEW.approval <> OLD.approval AND NEW.approval = 'yes') then
       set new.approval_time=now() ;
     end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'116.111.62.21','2018-09-30 09:31:37','2018-09-30 02:31:37'),(2,'127.0.0.1','2018-10-01 14:00:14','2018-10-01 07:00:14'),(3,'123.456.789','2018-10-05 15:09:17','2018-10-05 08:09:17');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cmt_reaction`
--

DROP TABLE IF EXISTS `user_cmt_reaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cmt_reaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `cmt_id` int(10) unsigned NOT NULL,
  `reaction` enum('like','dislike') COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`cmt_id`,`reaction`),
  KEY `cmt_id` (`cmt_id`),
  CONSTRAINT `user_cmt_reaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_cmt_reaction_ibfk_2` FOREIGN KEY (`cmt_id`) REFERENCES `comment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cmt_reaction`
--

LOCK TABLES `user_cmt_reaction` WRITE;
/*!40000 ALTER TABLE `user_cmt_reaction` DISABLE KEYS */;
INSERT INTO `user_cmt_reaction` VALUES (50,2,77,'dislike','2018-10-03 15:29:15','2018-10-03 08:29:15');
/*!40000 ALTER TABLE `user_cmt_reaction` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_add_like_dislike_count_on_cmt

 AFTER INSERT ON `user_cmt_reaction`

 FOR EACH ROW

BEGIN
IF (NEW.`reaction` = 'like') THEN
 UPDATE `comment` set `like` = `like` + 1 where `id` = NEW.`cmt_id`;
 ELSEIF (NEW.`reaction` = 'dislike') THEN
 UPDATE `comment` set `dislike` = `dislike` + 1 where `id` = NEW.`cmt_id`;
 END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_update_like_dislike_count_on_cmt

 AFTER UPDATE ON `user_cmt_reaction`

 FOR EACH ROW

BEGIN
 IF (OLD.`reaction` = 'like' AND NEW.`reaction` = 'dislike' ) THEN
 UPDATE `comment` set `like` = `like` - 1 where `id` = OLD.`cmt_id`;
 UPDATE `comment` set `dislike` = `dislike` + 1 where `id` = OLD.`cmt_id`;
 ELSEIF (OLD.`reaction` = 'dislike' AND NEW.`reaction` = 'like') THEN
 UPDATE `comment` set `dislike` = `dislike` - 1 where `id` = OLD.`cmt_id`;
 UPDATE `comment` set `like` = `like` + 1 where `id` = OLD.`cmt_id`;
 END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `user_post_reaction`
--

DROP TABLE IF EXISTS `user_post_reaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_post_reaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `reaction` enum('like','dislike') COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`post_id`,`reaction`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `user_post_reaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_post_reaction_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_post_reaction`
--

LOCK TABLES `user_post_reaction` WRITE;
/*!40000 ALTER TABLE `user_post_reaction` DISABLE KEYS */;
INSERT INTO `user_post_reaction` VALUES (1,1,1,'dislike','2018-09-30 09:31:37','2018-10-01 03:59:36'),(2,1,2,'dislike','2018-09-30 15:13:32','2018-10-01 04:23:22'),(3,2,1,'dislike','2018-10-01 14:03:34','2018-10-02 03:43:22'),(4,2,2,'dislike','2018-10-01 19:19:54','2018-10-04 05:34:50'),(5,2,3,'like','2018-10-02 15:44:07','2018-10-03 03:42:22'),(6,2,5,'dislike','2018-10-03 10:19:04','2018-10-03 03:23:09'),(7,2,4,'like','2018-10-03 10:21:09','2018-10-03 08:52:45'),(8,2,6,'like','2018-10-03 10:21:26','2018-10-03 03:21:26');
/*!40000 ALTER TABLE `user_post_reaction` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_add_like_dislike_count

 AFTER INSERT ON `user_post_reaction`

 FOR EACH ROW

BEGIN
IF (NEW.`reaction` = 'like') THEN
 UPDATE `post` set `like` = `like` + 1 where `id` = NEW.`post_id`;
 ELSEIF (NEW.`reaction` = 'dislike') THEN
 UPDATE `post` set `dislike` = `dislike` + 1 where `id` = NEW.`post_id`;
 END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_update_like_dislike_count

 AFTER UPDATE ON `user_post_reaction`

 FOR EACH ROW

BEGIN
 IF (OLD.`reaction` = 'like' AND NEW.`reaction` = 'dislike' ) THEN
 UPDATE `post` set `like` = `like` - 1 where `id` = OLD.`post_id`;
 UPDATE `post` set `dislike` = `dislike` + 1 where `id` = OLD.`post_id`;
 ELSEIF (OLD.`reaction` = 'dislike' AND NEW.`reaction` = 'like') THEN
 UPDATE `post` set `dislike` = `dislike` - 1 where `id` = OLD.`post_id`;
 UPDATE `post` set `like` = `like` + 1 where `id` = OLD.`post_id`;
 END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trigger_delete_like_dislike_count

 AFTER DELETE ON `user_post_reaction`

 FOR EACH ROW

BEGIN
IF (OLD.`reaction` = 'like') THEN
 UPDATE `post` set `like` = `like` - 1 where `id` = OLD.`post_id`;
 ELSEIF (OLD.`reaction` = 'dislike') THEN
 UPDATE `post` set `dislike` = `dislike` - 1 where `id` = OLD.`post_id`;
 END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping events for database 'confession'
--

--
-- Dumping routines for database 'confession'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-05 17:22:16
