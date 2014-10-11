/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

var fonts = {};


var resources = [
];
var symbols = {
"stage": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
         dom: [
         {
            id:'contraption',
            type:'rect',
            rect:['11','79','auto','auto','auto','auto']
         },
         {
            id:'logo',
            type:'rect',
            rect:['0','49','auto','auto','auto','auto']
         }],
         symbolInstances: [
         {
            id:'logo',
            symbolName:'logo'
         },
         {
            id:'contraption',
            symbolName:'contraption'
         }
         ]
      },
   states: {
      "Base State": {
         "${_logo}": [
            ["transform", "scaleX", '1.13273'],
            ["style", "top", '56px'],
            ["transform", "scaleY", '1.13273'],
            ["style", "left", '36px']
         ],
         "${_Stage}": [
            ["color", "background-color", 'rgba(255,255,255,1)'],
            ["style", "width", '1000px'],
            ["style", "height", '600px'],
            ["style", "overflow", 'hidden']
         ],
         "${_contraption}": [
            ["style", "left", '0px'],
            ["style", "top", '0px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 1000,
         autoPlay: true,
         timeline: [
            { id: "eid123", tween: [ "transform", "${_logo}", "scaleY", '1.13273', { fromValue: '1.13273'}], position: 1000, duration: 0, easing: "easeInBack" },
            { id: "eid125", tween: [ "style", "${_logo}", "top", '56px', { fromValue: '56px'}], position: 1000, duration: 0, easing: "easeInBack" },
            { id: "eid124", tween: [ "style", "${_logo}", "left", '36px', { fromValue: '36px'}], position: 1000, duration: 0, easing: "easeInBack" },
            { id: "eid122", tween: [ "transform", "${_logo}", "scaleX", '1.13273', { fromValue: '1.13273'}], position: 1000, duration: 0, easing: "easeInBack" },
            { id: "eid108", trigger: [ function executeSymbolFunction(e, data) { this._executeSymbolAction(e, data); }, ['stop', '${_contraption}', [] ], ""], position: 0 },
            { id: "eid109", trigger: [ function executeSymbolFunction(e, data) { this._executeSymbolAction(e, data); }, ['play', '${_contraption}', [0] ], ""], position: 1000 }         ]
      }
   }
},
"contraption": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
   dom: [
   {
      id: 'Layer-1',
      type: 'image',
      rect: ['0px','235px','187px','216px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/Layer-1.jpg','0px','0px']
   },
   {
      id: 'Layer-2',
      type: 'image',
      rect: ['162px','295px','34px','38px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/Layer-2.jpg','0px','0px']
   },
   {
      id: 'Layer-3',
      type: 'image',
      rect: ['212px','212px','274px','233px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/Layer-3.jpg','0px','0px']
   },
   {
      id: 'Layer-4',
      type: 'image',
      rect: ['490px','270px','42px','44px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/Layer-4.jpg','0px','0px']
   },
   {
      id: 'man-on-trike',
      type: 'rect',
      rect: ['auto','auto','auto','auto','1000px','461px']
   }],
   symbolInstances: [
   {
      id: 'man-on-trike',
      symbolName: 'man-on-trike'
   }   ]
   },
   states: {
      "Base State": {
         "${_man-on-trike}": [
            ["style", "top", 'auto'],
            ["transform", "scaleY", '1'],
            ["style", "bottom", '461px'],
            ["transform", "scaleX", '1'],
            ["style", "opacity", '1'],
            ["style", "left", 'auto'],
            ["style", "right", '1000px']
         ],
         "${_Layer-4}": [
            ["style", "top", '270px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '1'],
            ["style", "left", '490px']
         ],
         "${symbolSelector}": [
            ["style", "height", '600px'],
            ["style", "width", '1000px']
         ],
         "${_Layer-1}": [
            ["style", "top", '235px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '1'],
            ["style", "left", '0px']
         ],
         "${_Layer-3}": [
            ["style", "top", '212px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '1'],
            ["style", "left", '212px']
         ],
         "${_Layer-2}": [
            ["style", "top", '295px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '1'],
            ["style", "left", '162px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 5500,
         autoPlay: true,
         timeline: [
            { id: "eid19", tween: [ "transform", "${_Layer-1}", "scaleY", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid4", tween: [ "style", "${_Layer-1}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 500 },
            { id: "eid18", tween: [ "transform", "${_Layer-1}", "scaleX", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid8", tween: [ "style", "${_Layer-2}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 500 },
            { id: "eid22", tween: [ "transform", "${_Layer-2}", "scaleX", '1', { fromValue: '0'}], position: 1000, duration: 500, easing: "easeOutBack" },
            { id: "eid31", tween: [ "transform", "${_Layer-4}", "scaleY", '1', { fromValue: '0'}], position: 2500, duration: 500, easing: "easeOutBack" },
            { id: "eid10", tween: [ "style", "${_Layer-3}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 500 },
            { id: "eid23", tween: [ "transform", "${_Layer-2}", "scaleY", '1', { fromValue: '0'}], position: 1000, duration: 500, easing: "easeOutBack" },
            { id: "eid26", tween: [ "transform", "${_Layer-3}", "scaleX", '1', { fromValue: '0'}], position: 1750, duration: 500, easing: "easeOutBack" },
            { id: "eid27", tween: [ "transform", "${_Layer-3}", "scaleY", '1', { fromValue: '0'}], position: 1750, duration: 500, easing: "easeOutBack" },
            { id: "eid2", tween: [ "style", "${_Layer-4}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 500 },
            { id: "eid30", tween: [ "transform", "${_Layer-4}", "scaleX", '1', { fromValue: '0'}], position: 2500, duration: 500, easing: "easeOutBack" }         ]
      }
   }
},
"man-on-trike": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
   dom: [
   {
      id: 'man',
      type: 'rect',
      clip: ['rect(0px 245pxpx 366pxpx 0px)'],
      rect: ['541','-15','auto','auto','auto','auto']
   },
   {
      id: 'scarf',
      type: 'image',
      rect: ['664','39px','100px','40px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/scarf.png','0px','0px']
   },
   {
      id: 'scarfwave',
      type: 'rect',
      rect: ['663','39','auto','auto','auto','auto']
   }],
   symbolInstances: [
   {
      id: 'man',
      symbolName: 'scarf-wave'
   },
   {
      id: 'scarfwave',
      symbolName: 'scarfwave'
   }   ]
   },
   states: {
      "Base State": {
         "${_man}": [
            ["style", "top", '-15px'],
            ["style", "left", '541px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '1'],
            ["style", "clip", [0,245,366,0], {valueTemplate:'rect(@@0@@px @@1@@px @@2@@px @@3@@px)'} ]
         ],
         "${_scarfwave}": [
            ["transform", "scaleX", '0'],
            ["style", "opacity", '0'],
            ["transform", "scaleY", '0'],
            ["style", "left", '663px']
         ],
         "${symbolSelector}": [
            ["style", "height", '0px'],
            ["style", "width", '0px']
         ],
         "${_scarf}": [
            ["style", "top", '39px'],
            ["transform", "scaleY", '0'],
            ["transform", "scaleX", '0'],
            ["style", "opacity", '0'],
            ["style", "left", '663px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 2000,
         autoPlay: false,
         timeline: [
            { id: "eid71", tween: [ "transform", "${_scarf}", "scaleX", '1', { fromValue: '0'}], position: 250, duration: 250, easing: "easeOutBack" },
            { id: "eid55", tween: [ "style", "${_scarfwave}", "opacity", '1', { fromValue: '0'}], position: 1000, duration: 500, easing: "easeOutBack" },
            { id: "eid75", tween: [ "style", "${_scarfwave}", "opacity", '0', { fromValue: '1'}], position: 1750, duration: 250 },
            { id: "eid73", tween: [ "style", "${_man}", "opacity", '0', { fromValue: '1'}], position: 1750, duration: 250 },
            { id: "eid70", tween: [ "transform", "${_man}", "scaleX", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid78", tween: [ "transform", "${_scarfwave}", "scaleY", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid131", tween: [ "style", "${_scarf}", "opacity", '1', { fromValue: '0'}], position: 0, duration: 500 },
            { id: "eid128", tween: [ "style", "${_scarf}", "opacity", '0', { fromValue: '1'}], position: 1000, duration: 500 },
            { id: "eid129", tween: [ "style", "${_scarf}", "opacity", '0', { fromValue: '0'}], position: 2000, duration: 0 },
            { id: "eid93", tween: [ "style", "${_man}", "left", '-267px', { fromValue: '541px'}], position: 1000, duration: 1000, easing: "easeInBack" },
            { id: "eid77", tween: [ "transform", "${_scarf}", "scaleY", '1', { fromValue: '0'}], position: 250, duration: 250, easing: "easeOutBack" },
            { id: "eid97", tween: [ "style", "${_scarfwave}", "left", '-150px', { fromValue: '663px'}], position: 1000, duration: 1000, easing: "easeInBack" },
            { id: "eid98", tween: [ "style", "${_scarf}", "left", '-150px', { fromValue: '663px'}], position: 1000, duration: 1000, easing: "easeInBack" },
            { id: "eid72", tween: [ "transform", "${_scarfwave}", "scaleX", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid76", tween: [ "transform", "${_man}", "scaleY", '1', { fromValue: '0'}], position: 0, duration: 500, easing: "easeOutBack" },
            { id: "eid79", trigger: [ function executeSymbolFunction(e, data) { this._executeSymbolAction(e, data); }, ['stop', '${_scarfwave}', [] ], ""], position: 0 },
            { id: "eid57", trigger: [ function executeSymbolFunction(e, data) { this._executeSymbolAction(e, data); }, ['play', '${_scarfwave}', [0] ], ""], position: 1000 },
            { id: "eid56", trigger: [ function executeSymbolFunction(e, data) { this._executeSymbolAction(e, data); }, ['stop', '${_scarfwave}', [] ], ""], position: 1750 }         ]
      }
   }
},
"scarf-wave": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
   dom: [
   {
      rect: ['0px','0px','245px','366px','auto','auto'],
      id: 'Layer-5-12',
      opacity: 1,
      type: 'image',
      fill: ['rgba(0,0,0,0)','images/Layer-5-1.jpg','0px','0px']
   }],
   symbolInstances: [
   ]
   },
   states: {
      "Base State": {
         "${_Layer-5-12}": [
            ["style", "top", '0px'],
            ["style", "opacity", '1'],
            ["style", "left", '0px']
         ],
         "${symbolSelector}": [
            ["style", "height", '366px'],
            ["style", "width", '245px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 0,
         autoPlay: true,
         timeline: [
         ]
      }
   }
},
"scarfwave": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
   dom: [
   {
      rect: ['0px','0px','1200px','40px','auto','auto'],
      id: 'scarf-sprite-sheet2',
      type: 'image',
      clip: ['rect(0px 100px 40px 0px)'],
      fill: ['rgba(0,0,0,0)','images/scarf-sprite-sheet.png','0px','0px']
   }],
   symbolInstances: [
   ]
   },
   states: {
      "Base State": {
         "${_scarf-sprite-sheet2}": [
            ["style", "top", '0px'],
            ["style", "background-position", [0,0], {valueTemplate:'@@0@@px @@1@@px'} ],
            ["style", "left", '0px'],
            ["style", "clip", [0,100,40,0], {valueTemplate:'rect(@@0@@px @@1@@px @@2@@px @@3@@px)'} ]
         ],
         "${symbolSelector}": [
            ["style", "height", '40px'],
            ["style", "width", '1200px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 1199.7098770055,
         autoPlay: false,
         timeline: [
            { id: "eid40", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [0,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0,0]}], position: 0, duration: 0, easing: "easeOutBack" },
            { id: "eid41", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-100,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [0,0]}], position: 100, duration: 0, easing: "easeOutBack" },
            { id: "eid42", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-200,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-100,0]}], position: 200, duration: 0, easing: "easeOutBack" },
            { id: "eid43", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-300,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-200,0]}], position: 303, duration: 0, easing: "easeOutBack" },
            { id: "eid44", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-400,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-300,0]}], position: 404, duration: 0, easing: "easeOutBack" },
            { id: "eid45", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-500,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-400,0]}], position: 500, duration: 0, easing: "easeOutBack" },
            { id: "eid46", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-600,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-500,0]}], position: 601, duration: 0, easing: "easeOutBack" },
            { id: "eid47", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-700,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-600,0]}], position: 702, duration: 0, easing: "easeOutBack" },
            { id: "eid48", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-800,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-700,0]}], position: 802, duration: 0, easing: "easeOutBack" },
            { id: "eid49", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-900,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-800,0]}], position: 900, duration: 0, easing: "easeOutBack" },
            { id: "eid50", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-1000,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-900,0]}], position: 1000, duration: 0, easing: "easeOutBack" },
            { id: "eid51", tween: [ "style", "${_scarf-sprite-sheet2}", "background-position", [-1100,0], { valueTemplate: '@@0@@px @@1@@px', fromValue: [-1000,0]}], position: 1095, duration: 0, easing: "easeOutBack" }         ]
      }
   }
},
"logo": {
   version: "2.0.1",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.1.268",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
   dom: [
   {
      id: 'deconstruction_logo',
      type: 'image',
      rect: ['0px','0px','543px','88px','auto','auto'],
      fill: ['rgba(0,0,0,0)','images/deconstruction_logo.jpg','0px','0px']
   }],
   symbolInstances: [
   ]
   },
   states: {
      "Base State": {
         "${symbolSelector}": [
            ["style", "height", '88px'],
            ["style", "width", '543px']
         ],
         "${_deconstruction_logo}": [
            ["style", "top", '0px'],
            ["style", "height", '88px'],
            ["style", "opacity", '0'],
            ["style", "left", '0px'],
            ["style", "width", '543px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 1000,
         autoPlay: true,
         timeline: [
            { id: "eid107", tween: [ "style", "${_deconstruction_logo}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 500, easing: "easeInBack" }         ]
      }
   }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "edge-animation");
