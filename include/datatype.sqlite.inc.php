<option value="TEXT" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="TEXT"?"selected":'')?>>TEXT</option>
<option value="NUMERIC" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="NUMERIC"?"selected":'')?>>NUMERIC</option>
<option value="INTEGER" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="INTEGER"?"selected":'')?>>INTEGER</option>
<option value="BLOB" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BLOB"?"selected":'')?>>BLOB</option>
<option value="OTHER">OTHER...</option>
<option value="HASMANY" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="HASMANY"?"selected":'')?>>{ CHILD }</option>
<option value="BELONGSTO" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="BELONGSTO"?"selected":'')?>>{ PARENT }</option>
<option value="JOIN" <?php echo(isset($typeList)&&isset($typeList[$dataTypeIndex])&&$typeList[$dataTypeIndex]=="JOIN"?"selected":'')?>>{ SIBLING }</option>