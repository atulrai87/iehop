DROP TABLE IF EXISTS `[prefix]job_categories`;
CREATE TABLE IF NOT EXISTS `[prefix]job_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `parent` int(3) NOT NULL  DEFAULT '0',
  `name` varchar(200) NOT NULL,
  `sorter` tinyint(3) NOT NULL,
  `statistics` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('1', '0', 'Accounting/Finance', '1');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('2', '0', 'Administrative/Clerical', '2');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('3', '0', 'Business/Strategic Management', '3');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('4', '0', 'Building Construction/Skilled Trades', '4');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('5', '0', 'Creative/Design', '5');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('6', '0', 'Customer Support/Client Care', '6');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('7', '0', 'Editorial/Writing', '7');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('8', '0', 'Engineering', '8');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('9', '0', 'Food Services/Hospitality', '9');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('10', '0', 'Human Resources', '10');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('11', '0', 'Installation/Maintenance/Repair', '11');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('12', '0', 'IT/Software Development', '12');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('13', '0', 'Legal', '13');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('14', '0', 'Logistics/Transportation', '14');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('15', '0', 'Marketing/Product', '15');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('16', '0', 'Medical/Health', '16');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('17', '0', 'Production/Operations', '17');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('18', '0', 'Project/Program Management', '18');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('19', '0', 'Quality Assurance/Safety', '19');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('20', '0', 'R_D/Science', '20');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('21', '0', 'Sales/Business Development', '21');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('22', '0', 'Security/Protective Services', '22');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('23', '0', 'Training/Instruction', '23');
INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('24', '0', 'Other', '24');



INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('51', '3', 'Business Analysis/Research', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('64', '4', 'CAD/Drafting', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('78', '5', 'Advertising Writing/ Creative', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('89', '6', 'Account Management/ Non Commisioned', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('99', '7', 'Documentation/Technical Writing', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('105', '8', 'Aeronautic/Avionic Engineering', '1');



INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('126', '10', 'Other-General HR', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('133', '11', 'Computer/Electronics/Telecomm Install', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('144', '12', 'Web/UI/UX Design', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('160', '13', 'Barrister', '1');


INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('187', '15', 'Other-General Marketing/Product', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('199', '16', 'Other-General Medical And Health', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('216', '17', 'Layout/Prepress/Printing//Binding Op', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('233', '18', 'Event Planning/Coordination', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('239', '19', 'Building/Construction Inspection', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('249', '20', 'Biological/Chemical Research', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('257', '21', 'Account Management/Commisioned', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('273', '22', 'Customs/Immigration', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('281', '23', 'Academic Research', '1');

INSERT INTO `[prefix]job_categories` (id, parent, name, sorter) VALUES ('296', '24', 'Career Fair', '1');
