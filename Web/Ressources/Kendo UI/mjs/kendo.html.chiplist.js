/**
 * Kendo UI v2023.1.117 (http://www.telerik.com/kendo-ui)
 * Copyright 2023 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.html.base.js";var __meta__={id:"html.chiplist",name:"Html.ChipList",category:"web",description:"HTML rendering utility for Kendo UI for jQuery.",depends:["html.base"],features:[]};!function(i,e){var t=window.kendo,n=t.html.HTMLBase,s=n.extend({init:function(i,e){var t=this;n.fn.init.call(t,i,e),t.wrapper=t.element.addClass("k-chip-list"),t._addClasses()},options:{name:"HTMLChipList",size:"medium",stylingOptions:["size"]}});i.extend(t.html,{renderChipList:function(t,n){return(arguments[0]===e||i.isPlainObject(arguments[0]))&&(n=t,t=i("<div></div>")),new s(t,n).html()},HTMLChipList:s}),t.cssProperties.registerPrefix("HTMLChipList","k-chip-list-")}(window.kendo.jQuery);
//# sourceMappingURL=kendo.html.chiplist.js.map
