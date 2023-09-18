/**
 * Kendo UI v2023.1.117 (http://www.telerik.com/kendo-ui)
 * Copyright 2023 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.core.js";var __meta__={id:"dom",name:"Virtual DOM",category:"framework",depends:["core"],advanced:!0};!function(e){function t(){this.node=null}function n(){}t.prototype={remove:function(){this.node.parentNode&&this.node.parentNode.removeChild(this.node),this.attr={}},attr:{},text:function(){return""}},n.prototype={nodeName:"#null",attr:{style:{}},children:[],remove:function(){}};var o=new n;function r(e,t,n){this.nodeName=e,this.attr=t||{},this.children=n||[]}function i(e){this.nodeValue=String(e)}function d(e){this.html=e}r.prototype=new t,r.prototype.appendTo=function(e){for(var t=document.createElement(this.nodeName),n=this.children,r=0;r<n.length;r++)n[r].render(t,o);return e.appendChild(t),t},r.prototype.render=function(e,t){var n;if(t.nodeName!==this.nodeName)t.remove(),n=this.appendTo(e);else{var r;n=t.node;var i=this.children,d=i.length,s=t.children,h=s.length;if(Math.abs(h-d)>2)return void this.render({appendChild:function(n){e.replaceChild(n,t.node)}},o);for(r=0;r<d;r++)i[r].render(n,s[r]||o);for(r=d;r<h;r++)s[r].remove()}this.node=n,this.syncAttributes(t.attr),this.removeAttributes(t.attr)},r.prototype.syncAttributes=function(e){var t=this.attr;for(var n in t){var o=t[n],r=e[n];"style"===n?this.setStyle(o,r):o!==r&&this.setAttribute(n,o,r)}},r.prototype.setStyle=function(e,t){var n,o=this.node;if(t)for(n in e)e[n]!==t[n]&&(o.style[n]=e[n]);else for(n in e)o.style[n]=e[n]},r.prototype.removeStyle=function(e){var t=this.attr.style||{},n=this.node;for(var o in e)void 0===t[o]&&(n.style[o]="")},r.prototype.removeAttributes=function(e){var t=this.attr;for(var n in e)"style"===n?this.removeStyle(e.style):void 0===t[n]&&this.removeAttribute(n)},r.prototype.removeAttribute=function(e){var t=this.node;"style"===e?t.style.cssText="":"className"===e?t.className="":t.removeAttribute(e)},r.prototype.setAttribute=function(e,t){var n=this.node;void 0!==n[e]?n[e]=t:n.setAttribute(e,t)},r.prototype.text=function(){for(var e="",t=0;t<this.children.length;++t)e+=this.children[t].text();return e},i.prototype=new t,i.prototype.nodeName="#text",i.prototype.render=function(e,t){var n;t.nodeName!==this.nodeName?(t.remove(),n=document.createTextNode(this.nodeValue),e.appendChild(n)):(n=t.node,this.nodeValue!==t.nodeValue&&n.parentNode&&(n.nodeValue=this.nodeValue)),this.node=n},i.prototype.text=function(){return this.nodeValue},d.prototype={nodeName:"#html",attr:{},remove:function(){for(var e=0;e<this.nodes.length;e++){var t=this.nodes[e];t.parentNode&&t.parentNode.removeChild(t)}},render:function(e,t){if(t.nodeName!==this.nodeName||t.html!==this.html){t.remove();var n=e.lastChild;!function(e,t){s.innerHTML=t;for(;s.firstChild;)e.appendChild(s.firstChild)}(e,this.html),this.nodes=[];for(var o=n?n.nextSibling:e.firstChild;o;o=o.nextSibling)this.nodes.push(o)}else this.nodes=t.nodes.slice(0)}};var s=document.createElement("div");function h(e){return new d(e)}function a(e,t,n){return new r(e,t,n)}function l(e){return new i(e)}function u(e){this.root=e,this.children=[]}u.prototype={html:h,element:a,text:l,render:function(e){var t,n,r=this.children;for(t=0,n=e.length;t<n;t++){var i=r[t];i?i.node&&i.node.parentNode||(i.remove(),i=o):i=o,e[t].render(this.root,i)}for(t=n;t<r.length;t++)r[t].remove();this.children=e}},e.dom={html:h,text:l,element:a,Tree:u,Node:t}}(window.kendo);
//# sourceMappingURL=kendo.dom.js.map
