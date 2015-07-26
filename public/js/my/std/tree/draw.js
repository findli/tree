/**
 * Created by ya on 1/17/14.
 */
nestedTree = {};
nestedTree.getBreadcrumbsFromArray = function (breadcrumbsArray) {
    parentClass = breadcrumbsArray[breadcrumbsArray.length - (breadcrumbsArray.length - 1) - 1];
    $(breadcrumbsArray).each(function (k1, v1) {
        if (k1 != 0) {
            parentClass = parentClass + '-' + v1;
        }
    })
    return parentClass;
}
nestedTree.getArrayFromBreadcrumbs = function (breadcrumbsString) {
    return explode('-', breadcrumbsString);
}
function findUpTag(el, tag) {
    el = el[0];
    tag = tag.toUpperCase();
//    console.log('function findUpTag');
//    console.log(el);
//    console.log(tag);
    while (el.parentNode) {
        el = el.parentNode;
//				if ($(el).attr('hasnext') === 'has') {
//					console.log("$(el).attr('hasnext')");
//					console.log($(el).attr('hasnext'));
//					console.log('el.tagName', el.tagName);
//				}
        if (el.tagName === tag && $(el).attr('hasnext') === 'has') {
//					console.log($(el).attr('class'));
//					console.log('el', el);

            var cssClass = $(el).attr('class');
            el.removeAttribute('hasnext');
            return  cssClass;
        } else {
            if (el.tagName === tag && $(el).attr('hasnext') === 'not') {
                el.removeAttribute('hasnext');
            }
//					console.log($(el).attr('class'));
        }
    }
    return undefined;
}
nestedTree.getLi = function (newRowClass, dataObject) {
    console.log('dataObject:');
    console.log(dataObject);
    var ret = $(document.createElement('li')).attr('class', newRowClass)
        .attr('mongoid', dataObject._id.$id)
        .append($(document.createElement('span')).attr('class', 'thetext').text(dataObject.name + '; ' + newRowClass))
        .append($(document.createElement('input')).attr('type', 'hidden').attr('value', dataObject.name).attr('name', 'name'))
        .append($(document.createElement('input')).attr('type', 'hidden').attr('value', dataObject.description).attr('name', 'description'))
        .append($(document.createElement('input')).attr('type', 'hidden').attr('value', dataObject.slug).attr('name', 'slug'))
        .append($(document.createElement('input')).attr('type', 'button').attr('value', 'add').attr('target', 'add'))
        .append($(document.createElement('input')).attr('type', 'button').attr('value', 'del').attr('target', 'delete'))
        .append($(document.createElement('input')).attr('type', 'button').attr('value', 'edit').attr('target', 'edit'));
//    console.log('mongoid', id[$id]);
    return ret;
}
nestedTree_drawTree_tmp2 = undefined;
// parentClass use only once for definition tree place
// breadcrumbsArray contain elements from those can create parent class
nestedTree.drawTree = function (treeJson, breadcrumbsArray, parentClass) {
    /*
     slug это обозначение в URL.
     создается UL  с Id первого LI.
     ============================
     Зачем?
     ======================
     */
//    console.log('function drawTree(treeJson = ' + '' + ', breadcrumbsArray = ' + breadcrumbsArray + ', parentClass = ' + parentClass);
//    console.log('treeJson: ');
//    console.log(treeJson);
    if (parentClass == undefined) {
        parentClass = this.getBreadcrumbsFromArray(breadcrumbsArray)
    }

    var ulId = treeJson[0]._id.$id;
    var ul = $(document.createElement('ul')).attr('id', ulId);
    $("." + parentClass).append(ul);
    for (var i = 0; i < treeJson.length; i++) {
//    console.log('nestedTree_drawTree_tmp2: ', nestedTree_drawTree_tmp2);
        if (nestedTree_drawTree_tmp2 !== undefined) {
            breadcrumbsArray = nestedTree_drawTree_tmp2;
            nestedTree_drawTree_tmp2 = undefined;
        }
//        console.log("%c nestedTree_drawTree_tmp2 breadcrumbsArray: ", "color:green;", breadcrumbsArray);
        /*
         8) создается класс для текущего элемента в виде хлебных крошек, так что последнее значение из класса предыдущего элемента инкрементируется.
         было 1-1-1
         станет 1-1-2

         было 1-2-5
         станет 1-2-6
         */
        var k1 = i;
//				$(treeJson).each(function (k1, treeJson[i]) {
//				console.group('loop');
        ++k1;
        if (k1 == 1) {
//					console.log('push', k1);
            breadcrumbsArray.push(k1);
        } else {
//					console.log('removeKey', breadcrumbsArray.length - 1);
            breadcrumbsArray = removeKey(breadcrumbsArray, breadcrumbsArray.length - 1);
//					console.log('push', k1);
            breadcrumbsArray.push(k1);
        }
        /*
         9)        создается класс в htmlльном виде:
         из [1,1,2,4] делается 1-1-2-4
         */
//				console.log(breadcrumbsArray);
        var newRowClass = this.getBreadcrumbsFromArray(breadcrumbsArray);
//				console.log('%cnewRowClass', "color:blue;", newRowClass);
        /*
         10) создается LI с классом newRowClass 1-1-2-4 и атрибутом mongoid=Id текущего элемента при переборе treeJson
         */
        var li = this.getLi(newRowClass, treeJson[i]);
// tag 1
        /*
         11) помечается текущая Ли: если есть дети то hasnext=has иначе hasnext="not".
         ===================================
         Зачем?
         ================================
         */
        if (treeJson[i].child.length > 0 && treeJson[i + 1] != undefined) {
            $(li).attr('hasnext', 'has');
        } else {
            $(li).attr('hasnext', 'not');
        }

        // tag //1
        /*
         12) созданная Ли помещается в Ул текущего уровня
         */
        $("#" + ulId).append(li);
        /*
         13) если есть потомки, то рекурсия на построение следующего вертикального уровня
         */
        if (treeJson[i].child.length > 0) {
//					console.log('breadcrumbsArray', breadcrumbsArray);
            this.drawTree(treeJson[i].child, breadcrumbsArray);
        }
        // tag 1
        /*
         14) если весь уровень пройден
         */
        if (treeJson[i + 1] == undefined) {
//            console.log("last element newRowClass:");
//            console.log(newRowClass);
            var breadcrumbsString = findUpTag(document.getElementsByClassName(newRowClass), 'li');
//            console.log("\tbreadcrumbsString parent:", breadcrumbsString);
//            console.log("%c\tbreadcrumbsString parent:", "background-color:yellow;", breadcrumbsString);
            /*
             на текущем уровне вложенности в рекурсии проверяется есть ли следующие элементы на предыдущем уровне рекурсии
             и если есть(это определяется по тегу hasnext="has"), то в глобальную переменную nestedTree_drawTree_tmp2 заносится готовый следующий массив для обозначения класса
             */
            if (breadcrumbsString != undefined) {
//                console.log('%c\tbreadcrumbsString != undefined', "color:red;");
                breadcrumbsArray = this.getArrayFromBreadcrumbs(breadcrumbsString);
//						console.log('breadcrumbsArray');
//						console.log(breadcrumbsArray);

                var tmp1 = (breadcrumbsArray[breadcrumbsArray.length - 1]) * 1 + 1;

//						console.log('tmp1:', tmp1);
                breadcrumbsArray = removeKey(breadcrumbsArray, breadcrumbsArray.length - 1);
                breadcrumbsArray.push(tmp1 + '');

//						console.log('breadcrumbsArray');
//						console.log(breadcrumbsArray);
                nestedTree_drawTree_tmp2 = breadcrumbsArray;
            }

        }
        // tag //1
//				console.groupEnd('loop');
//				})
    }
//    console.log(parentClass);
}
