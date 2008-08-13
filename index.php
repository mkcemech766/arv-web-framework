<?session_start();?>
<script>
// Redirect for refreshes and direct
// landing with url changes.
if(window.location.hash && typeof(window.actualTemplate_xml)=='undefined'){
    document.cookie = 'url=' + window.location.hash.toString().replace('#', '');
    window.location = '/find-spots.com/' + window.location.hash.toString();
}
</script>

<!--AJAX navigation system.-->
<script src="include/js/ajax.js"></script>
<script src="include/js/navigation.js"></script>

<!--Prototype, hosted by Google.-->
 <script type="text/javascript" src="include/js/prototype-1.6.0.2.js"></script>

<!--AJAX history management.-->
<script type="text/javascript" src="include/js/rsh.js"></script>
<script type="text/javascript">
window.dhtmlHistory.create({
    toJSON: function(o) {
        return Object.toJSON(o);
    }
    , fromJSON: function(s){
        return s.evalJSON();
    }
});

// This bool is set because when the template is loading
// the location hash is not immediately changed. So, the
// verify location change interfer with the loading of
// the page.
var rshListener = function(){
    loadPage(window.location.hash.toString().replace('#', ''));
}

window.onload = function(){
    dhtmlHistory.initialize();
    dhtmlHistory.addListener(rshListener);
};
</script>

<?
include 'include/php/class/site.php';
include 'include/php/class/user.php';
include siteProperties::getClassPath() . 'structure.php';

// Set the default page to load with the template 
// system if the server variable is not set.
if(!isset($_COOKIE['url'])){
    $pageToLoad = 'index';
}else{
    foreach(explode('&', $_COOKIE['url']) as $urlVariable){
        if(eregi('^page=', $urlVariable)){
            $pageToLoad = str_replace('page=', '', $urlVariable);
        }
    }
}


$page_template =  buildStructure::html(array('page' => $pageToLoad));

echo '<a' .siteTools::generateAnchorAttributes(array('attributes' => array('style' => 'background-color:yellow;', 'href' => 'asdasd=asdasd&page=index&sauce=1'))) . '>asdasd</a>';
echo ' ';
echo '<a' .siteTools::generateAnchorAttributes(array('attributes' => array('style' => 'background-color:yellow;', 'href' => 'asdasd=asdasd&page=forum&sauce=2'))) . '>asdasd</a>';

echo $page_template;
?>

<!--Include the JavaScript file that contains
all the structure of the templates and of the pages.-->
<script>
<?=buildStructure::renderStructureAsJSObject(array('structureName' => 'page'));?>

<?=buildStructure::renderStructureAsJSObject(array('structureName' => 'template'));?>

window.actualUrlList_array = [];
window.actualTemplate_xml = page.<?=$pageToLoad?>();
processTemplateStructure(window.actualTemplate_xml, 'actual');
</script>
