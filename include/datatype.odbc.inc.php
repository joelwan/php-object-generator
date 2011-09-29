<option value="BIGINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BIGINT"?"selected":'')?>>BIGINT</option>
<option value="BIT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BIT"?"selected":'')?>>BIT</option>
<option value="CHAR" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="CHAR"?"selected":'')?>>CHAR</option>
<option value="DATETIME" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DATETIME"?"selected":'')?>>DATETIME</option>
<option value="DECIMAL" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="DECIMAL"?"selected":'')?>>DECIMAL</option>
<option value="FLOAT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="FLOAT"?"selected":'')?>>FLOAT</option>
<option value="IMAGE" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="IMAGE"?"selected":'')?>>IMAGE</option>
<option value="INT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="INT"?"selected":'')?>>INT</option>
<option value="MONEY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="MONEY"?"selected":'')?>>MONEY</option>
<option value="NCHAR" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="NCHAR"?"selected":'')?>>NCHAR</option>
<option value="NTEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="NTEXT"?"selected":'')?>>NTEXT</option>
<option value="NUMERIC" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="NUMERIC"?"selected":'')?>>NUMERIC</option>
<option value="NVARCHAR" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="NVARCHAR"?"selected":'')?>>NVARCHAR</option>
<option value="REAL" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="REAL"?"selected":'')?>>REAL</option>
<option value="SMALLDATETIME" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="SMALLDATETIME"?"selected":'')?>>SMALLDATETIME</option>
<option value="SMALLINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="SMALLINT"?"selected":'')?>>SMALLINT</option>
<option value="SMALLMONEY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="SMALLMONEY"?"selected":'')?>>SMALLMONEY</option>
<option value="TEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TEXT"?"selected":'')?>>TEXT</option>
<option value="TIMESTAMP" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TIMESTAMP"?"selected":'')?>>TIMESTAMP</option>
<option value="TINYINT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TINYINT"?"selected":'')?>>TINYINT</option>
<option value="UNIQUEIDENTIFIER" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="UNIQUEIDENTIFIER"?"selected":'')?>>UNIQUEIDENTIFIER</option>
<option value="VARBINARY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="VARBINARY"?"selected":'')?>>VARBINARY</option>
<option value="VARCHAR(255)" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="VARCHAR(255)"?"selected":'')?>>VARCHAR(255)</option>
<option value="OTHER">OTHER...</option>
<option value="HASMANY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="HASMANY"?"selected":'')?>>{ CHILD }</option>
<option value="BELONGSTO" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BELONGSTO"?"selected":'')?>>{ PARENT }</option>
<option value="JOIN" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="JOIN"?"selected":'')?>>{ SIBLING }</option>