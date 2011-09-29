<option value="VARCHAR(255)" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="VARCHAR(255)"?"selected":'')?>>VARCHAR(255)</option>
<option value="TINYINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=='TINYINT'?"selected":'')?>>TINYINT</option>
<option value="TEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TEXT"?"selected":'')?>>TEXT</option>
<option value="DATE" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DATE"?"selected":'')?>>DATE</option>
<option value="SMALLINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="SMALLINT"?"selected":'')?>>SMALLINT</option>
<option value="MEDIUMINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="MEDIUMINT"?"selected":'')?>>MEDIUMINT</option>
<option value="INT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="INT"?"selected":'')?>>INT</option>
<option value="BIGINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BIGINT"?"selected":'')?>>BIGINT</option>
<option value="FLOAT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="FLOAT"?"selected":'')?>>FLOAT</option>
<option value="DOUBLE" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DOUBLE"?"selected":'')?>>DOUBLE</option>
<option value="DECIMAL" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DECIMAL"?"selected":'')?>>DECIMAL</option>
<option value="DATETIME" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DATETIME"?"selected":'')?>>DATETIME</option>
<option value="TIMESTAMP" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TIMESTAMP"?"selected":'')?>>TIMESTAMP</option>
<option value="TIME" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TIME"?"selected":'')?>>TIME</option>
<option value="YEAR" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="YEAR"?"selected":'')?>>YEAR</option>
<option value="CHAR(255)" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="CHAR(255)"?"selected":'')?>>CHAR(255)</option>
<option value="TINYBLOB" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TINYBLOB"?"selected":'')?>>TINYBLOB</option>
<option value="TINYTEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TINYTEXT"?"selected":'')?>>TINYTEXT</option>
<option value="BLOB" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BLOB"?"selected":'')?>>BLOB</option>
<option value="MEDIUMBLOB" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="MEDIUMBLOB"?"selected":'')?>>MEDIUMBLOB</option>
<option value="MEDIUMTEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="MEDIUMTEXT"?"selected":'')?>>MEDIUMTEXT</option>
<option value="LONGBLOB" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="LONGBLOB"?"selected":'')?>>LONGBLOB</option>
<option value="LONGTEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="LONGTEXT"?"selected":'')?>>LONGTEXT</option>
<option value="BINARY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BINARY"?"selected":'')?>>BINARY</option>
<option value="OTHER">OTHER...</option>
<option value="HASMANY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="HASMANY"?"selected":'')?>>{ CHILD }</option>
<option value="BELONGSTO" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BELONGSTO"?"selected":'')?>>{ PARENT }</option>
<option value="JOIN" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="JOIN"?"selected":'')?>>{ SIBLING }</option>