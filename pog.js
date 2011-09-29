
function AddField(){trs=document.getElementsByTagName("div")
for(var w=0;w<trs.length;w++){if(trs[w].style.display=="none"){trs[w].style.display="block"
var control=document.getElementById("field"+trs[w].id)
try{control.focus()}
catch(e){}
break}}}
function ResetFields(){trs=document.getElementsByTagName("input")
for(var w=0;w<trs.length;w++){trs[w].value=""}}
function ConvertDDLToTextfield(id){var thisId=id
trs=document.getElementsByTagName("select")
for(var w=0;w<trs.length;w++){if(trs[w].id==thisId){if(trs[w].value=="OTHER"){trs[w].style.display="none"
trs2=document.getElementsByTagName("input")
for(var v=0;v<trs2.length;v++){if(trs2[v].id=="t"+thisId){trs2[v].style.display="inline"
trs2[v].focus()
break}}}
break}}}
function FocusOnFirstField(){trs2=document.getElementById("FirstField")
trs2.focus()}
function IsPDO(){trs2=document.getElementById("wrapper")
if(trs2.value.toUpperCase()=="PDO"){link=document.getElementById("disappear")
link.style.display="none"
trs2=document.getElementById("PDOdriver")
trs2.value="mysql"
trs2.style.display="inline"}
else{select=document.getElementById("PDOdriver")
select.style.display="none"
link=document.getElementById("disappear")
link.style.display="inline"}}
function CascadePhpVersion(){trs2=document.getElementById("FirstField")
select=document.getElementById("wrapper")
select.length=0
if(trs2.value=="php5.1"){optionsArray=new Array("PDO","POG")}
else{optionsArray=new Array("POG")}
for(var i=0;i<optionsArray.length;i++){NewOpt=new Option
NewOpt.value=optionsArray[i].toLowerCase()
NewOpt.text=optionsArray[i]
select.options[i]=NewOpt}
IsPDO()}
function GenerateSQLTypesForDriver(driver){for(var j=1;j<100;j++){ddlist=document.getElementById("type_"+j)
ddlist.length=0
switch(driver){case"mysql":optionsArray=new Array("VARCHAR(255)","TINYINT","TEXT","DATE","SMALLINT","MEDIUMINT","INT","BIGINT","FLOAT","DOUBLE","DECIMAL","DATETIME","TIMESTAMP","TIME","YEAR","CHAR(255)","TINYBLOB","TINYTEXT","BLOB","MEDIUMBLOB","MEDIUMTEXT","LONGBLOB","LONGTEXT","BINARY","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break
case"oci":break
case"dblib":optionsArray=new Array("BIGINT","BINARY","BIT","CHAR","DATETIME","DECIMAL","FLOAT","IMAGE","INT","MONEY","NCHAR","NTEXT","NUMERIC","NVARCHAR","REAL","SMALLDATETIME","SMALLINT","SMALLMONEY","TEXT","TIMESTAMP","TINYINT","UNIQUEIDENTIFIER","VARBINARY","VARCHAR(255)","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break
case"firebird":optionsArray=new Array("BLOB","CHAR","CHAR(1)","TIMESTAMP","DECIMAL","DOUBLE","FLOAT","INT64","INTEGER","NUMERIC","SMALLINT","VARCHAR(255)","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break
case"odbc":optionsArray=new Array("BIGINT","BINARY","BIT","CHAR","DATETIME","DECIMAL","FLOAT","IMAGE","INT","MONEY","NCHAR","NTEXT","NUMERIC","NVARCHAR","REAL","SMALLDATETIME","SMALLINT","SMALLMONEY","TEXT","TIMESTAMP","TINYINT","UNIQUEIDENTIFIER","VARBINARY","VARCHAR(255)","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break
case"pgsql":optionsArray=new Array("BIGINT","BIGSERIAL","BIT","BOOLEAN","BOX","BYTEA","CIDR","CIRCLE","DATE","DOUBLE PRECISION","INET","INTEGER","LINE","LSEG","MACADDR","MONEY","OID","PATH","POINT","POLYGON","REAL","SMALLINT","SERIAL","TEXT","VARCHAR(255)","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break
case"sqlite":optionsArray=new Array("TEXT","NUMERIC","INTEGER","BLOB","OTHER","{ CHILD }","{ PARENT }","{ SIBLING }")
break}
for(var i=0;i<optionsArray.length;i++){NewOpt=new Option
if(optionsArray[i]=="{ CHILD }"){NewOpt.value="HASMANY"}
else if(optionsArray[i]=="{ PARENT }"){NewOpt.value="BELONGSTO"}
else if(optionsArray[i]=="{ SIBLING }"){NewOpt.value="JOIN"}
else{NewOpt.value=optionsArray[i]}
NewOpt.text=optionsArray[i]
ddlist.options[i]=NewOpt}}}
function Reposition(field,evt){var keyCode=document.layers?evt.which:document.all?event.keyCode:document.getElementById?evt.keyCode:0
var r=''
var fieldNameParts=field.name.split("_")
if(keyCode==40){Swap(field.name,"fieldattribute_"+(parseInt(fieldNameParts[1])+1))}
else if(keyCode==38){Swap(field.name,"fieldattribute_"+(parseInt(fieldNameParts[1])-1))}
return false}
function Swap(fieldName1,fieldName2){var fieldNameParts=fieldName1.split("_")
var attribute1=document.getElementsByName("fieldattribute_"+fieldNameParts[1])
var cf1=document.getElementsByName("ttype_"+fieldNameParts[1])
var type1=document.getElementsByName("type_"+fieldNameParts[1])
fieldNameParts=fieldName2.split("_")
var attribute2=document.getElementsByName("fieldattribute_"+fieldNameParts[1])
var cf2=document.getElementsByName("ttype_"+fieldNameParts[1])
var type2=document.getElementsByName("type_"+fieldNameParts[1])
var temp1=attribute1[0].value
if(cf1[0].value!=""){var temp2=cf1[0].value}
else{var temp2=type1[0].value}
attribute1[0].value=attribute2[0].value
attribute2[0].value=temp1
var temp3=cf1[0].value
if(cf2[0].value!=""){cf1[0].value=cf2[0].value
type1[0].style.display="none"
cf1[0].style.display="inline"}
else{for(var w=0;w<type1[0].length;w++){if(type1[0].options[w].value==type2[0].value){type1[0].selectedIndex=w
break}}
type1[0].style.display="inline"
cf1[0].value=""
cf1[0].style.display="none"}
if(temp3!=""){cf2[0].value=temp2
type2[0].style.display="none"
cf2[0].style.display="inline"}
else{for(var w=0;w<type2[0].length;w++){if(type2[0].options[w].value==temp2){type2[0].selectedIndex=w
break}}
type2[0].style.display="inline"
cf2[0].value=""
cf2[0].style.display="none"}
attribute2[0].focus()}
function WarnMinInput()
{var inputCount=0;trs=document.getElementsByTagName("input");var allVals=new Array();var allCount=0;for(var w=0;w<trs.length;w++)
{if(trs[w].value!=""&&trs[w].type!="hidden"&&trs[w].name!="object")
{inputCount++;if(allVals,trs[w].value != '' && InArray(allVals,trs[w].value))
{alert("Warning:\nYou have more than 1 attribute with the same value. Attributes must be unique.");return;}
else if(trs[w].value == document.getElementById('objName').value)
{alert("An object cannot relate to itself recursively. Make sure attribute names are different from the object name.");return;}
else
{allVals.push(trs[w].value);allCount++;}}}
if(inputCount>0)
{var typeCount=0;trs=document.getElementsByTagName("select");for(var w=0;w<trs.length;w++)
{if(trs[w].value=="HASMANY"||trs[w].value=="BELONGSTO"||trs[w].value=="JOIN")
{typeCount++;}}
if(typeCount>=inputCount)
{alert("Warning:\nYou need to have at least 1 non-parent/child attribute. Else POG will generate an invalid PHP object");}}
else
{alert("Warning:\nWithout any object attributes, POG may generate an invalid PHP object. You need to have at least 1 non-parent/child attribute");}
return false;}
function InArray(array,val)
{var found=false;for(var i=0;i<array.length;i++)
{if(array[i]==val)
{found=true;break;}}
return found;}