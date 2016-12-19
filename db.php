<?php 
//(1)数据查询总结

fetchRow();//这个方法返回一行，具体返回是相关数组还是什么用setFetchMode()决定
// fetchCol()返回第一列
// fetchOne()返回第一行，第一列。为一个值不是数组
// fetchAssoc()返回相关数组，相当于fetchAll()默认情况下的返回值 
// 直接进行查询. ( 使用完整的sql语句)
function quoteInto($text, $value, $type = null, $count = null)
$db = $this->getAdapter();
$sql = $db->quoteInto(‘SELECT * FROM `m_video` WHERE `is_guo` =?’, ’1′);
$result = $db->query($sql);
// 使用PDOStatement对象$result将所有结果数据放到一个数组中
$videoArray = $result->fetchAll();
// fetchAll用法
// fetchAll($where = null, $order = null, $count = null, $offset = null)
// 取回结果集中所有字段的值,作为连续数组返回,如果参数不设置就写成null
// 可以取回结果集的指定条数
$videoArray=$this->fetchAll(“is_jian=1 and is_guo=1″,”id DESC”,0,2)->toArray();

// fetchAssoc用法
// fetchAssoc($sql, $bind = array())
// 取回结果集中所有字段的值,作为关联数组返回, 第一个字段作为码
$db = $this->getAdapter();
$videoArray=$db->fetchAssoc(“SELECT * FROM m_video WHERE `is_jian` = :title”,array(‘title’ => ’1′));

// fetchCol用法
// fetchCol($sql, $bind = array())
// 取回所有结果行的第一个字段名
$db = $this->getAdapter();
$videoArray=$db->fetchCol(“SELECT name FROM m_video WHERE `is_jian` = :title”,array(‘title’ => ’1′));

// fetchOne用法
// fetchOne($sql, $bind = array())
// 只取回第一个字段值
$db = $this->getAdapter();
echo $videoArray=$db->fetchOne(“SELECT count(*) FROM m_video WHERE `is_jian` = :title”,array(‘title’ => ’1′));

// fetchPairs用法
// fetchPairs($sql, $bind = array())
// 取回一个相关数组,第一个字段值为码(id),第二个字段为值(name)
// 返回：Array( [1] => 十二生肖奇缘    [2] => 桃花运)，1,2:为id字段。
$db = $this->getAdapter();
$videoArray=$db->fetchPairs(“SELECT id, name FROM m_video WHERE is_jian = :title”,array(‘title’ => ’1′));

// fetchRow用法
// fetchRow($where = null, $order = null)
// 只取回结果集的第一行
$videoArray=$this->fetchRow(“is_jian=1 and is_guo=1″, ‘id DESC’)->toArray();
// query用法
//function query($sql, $bind = array())
$db = $this->getAdapter();
$result = $db->query(‘SELECT * FROM `m_video`’);
//$result = $db->query(‘SELECT * FROM `m_video` WHERE `name` = ? AND id = ?’,array(‘十二生肖奇缘’, ’1′));
//$result->setFetchMode(Zend_Db::FETCH_OBJ);//FETCH_OBJ为默认值,FETCH_NUM,FETCH_BOTH
//while ($row = $result->fetch()) {
//    echo $row['name'];
//}
//$rows = $result->fetch();
//$rows = $result->fetchAll();
//$obj = $result->fetchObject();//echo $obj->name;
// echo $Column = $result->fetchColumn(0);//得到结果集的第一个字段，比如0为id号,用于只取一个字段的情况
// print_r($rows);
// select用法
$db = $this->getAdapter();
$select = $db->select();
$select->from(‘m_video’, array(‘id’,'name’,'clicks’))
->where(‘is_guo = :is_guo and name = :name’)
->order(‘name’)// 按什么排序列，参加为数组(多个字段)或字符串(一个字段)
->group()//分组
->having()//分组查询数据的条件
->distinct()// 无参数，去掉重复的值。有时候与groupby返回的结果一样
->limit(10);
// 读取结果使用绑定的参数
$params = array(‘is_guo’ => ’1′,’name’=>’十二生肖奇缘’);
$sql = $select->__toString();//得到查询语句，可供调试
$result = $db->fetchAll($select,$params);
// 执行select的查询
$stmt = $db->query($select);

$result = $stmt->fetchAll();
// 或用
$stmt = $select->query();
$result = $stmt->fetchAll();
// 如果直接用
$db->fetchAll($select)结果一样
// 多表联合查询用法
$db = $this->getAdapter();
$select = $db->select();
$select->from(m_video, array(id,name,pic,actor,type_id,up_time))
->where(‘is_guo = :is_guo and is_jian = :is_jian’)
->order(‘up_time’)
->limit(2);
$params = array(‘is_guo’ => ’1′,’is_jian’=>’1′);
$select->join(‘m_type’, ‘m_video.type_id = m_type.t_id’, ‘type_name’);//多表联合查询
$videoArray = $db->fetchAll($select,$params);
// find()方法,可以使用主键值在表中检索数据.
// SELECT * FROM round_table WHERE id = “1″
$row = $table->find(1);
// SELECT * FROM round_table WHERE id IN(“1″, “2″, 3″)
$rowset = $table->find(array(1, 2, 3));

// (2)数据删除总结
// 第一种方法：可以删任意表
//quoteInto($text, $value, $type = null, $count = null)
$table = ‘m_video’;// 设定需要删除数据的表
$db = $this->getAdapter();
$where = $db->quoteInto(‘name = ?’, ‘ccc’);// 删除数据的where条件语句
echo $rows_affected = $db->delete($table, $where);// 删除数据并得到影响的行数
// 第二种方法：只能删除本表中的
//delete用法
// delete($where)
$where = “name = ‘bbb’”;
echo $this->delete($where);// 删除数据并得到影响的行数
(3)数据更新总结
// 第一种方法：可以更新任意表
// 以”列名”=>”数据”的格式构造更新数组,更新数据行
$table = ‘m_video’;// 更新的数据表
$db = $this->getAdapter();
$set = array (
‘name’ => ‘蝶影重重’,
‘clicks’ => ’888′,
);
$where = $db->quoteInto(‘id = ?’, ’10′);// where语句
// 更新表数据,返回更新的行数
echo $rows_affected = $db->update($table, $set, $where);

// 第二种方法：只能更新本表中的
$set = array (
‘name’ => ‘蝶影重重22′,
‘clicks’ => ’8880′,
);
$db = $this->getAdapter();
$where = $db->quoteInto(‘id = ?’, ’10′);// where语句
$rows_affected = $this->update($set, $where);// 更新表数据,返回更新的行数
// (4)数据插入总结
// 第一种方法：可以在任意表中插入数据
$table = ‘m_gao’;// 插入数据的数据表
$db = $this->getAdapter();
// 以”列名”=>”数据”的格式格式构造插入数组,插入数据行
$row = array (
‘title’     => ‘大家好。111′,
‘content’ => ‘影视网要改成用zend framework开发啊’,
‘time’ => ’2009-05-04 17:23:36′,
);
// 插入数据行并返回插入的行数
$rows_affected = $db->insert($table, $row);
// 最后插入的数据id
echo $last_insert_id = $db->lastInsertId();
$row=array(
‘name’=>’curdate()’,
‘address’ => new Zend_Db_Expr (‘curdate()’)
)
// 这样子字段name会插入一个curdate()的字符串，而address插入一个时间值(curdate()的结果2009-05-09)
// 第二种方法：只能适合本表中的还没有总结出来
// (5)事务处理
$table = ‘m_gao’;// 插入数据的数据表
$db = $this->getAdapter();
$db->beginTransaction();//Zend_Db_Adapter会回到自动commit模式下，直到你再次调用 beginTransaction()方法
// 以”列名”=>”数据”的格式格式构造插入数组,插入数据行
$row = array (
‘id’=>null,
‘title’     => ‘大家好。111′,
‘content’ => ‘影视网要改成用zend framework开发啊’,
‘time’ => ’2009-05-04 17:23:36′,
);
try {
// 插入数据行并返回插入的行数
$rows_affected = $db->insert($table, $row);
// 最后插入的数据id
$last_insert_id = $db->lastInsertId();
$db->commit();// 事务提交
}catch (Exception $e){
$db->rollBack();
echo ‘捕获异常：’.$e->getMessage();//打出异常信息
}
echo $last_insert_id;
// (5)其他
$db = $this->getAdapter();
$tables = $db->listTables(); //列出当前数据库中的所有表
$fields = $db->describeTable(‘m_video’);//列出一个表的字段情况