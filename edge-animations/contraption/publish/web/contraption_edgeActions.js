
(function($,Edge,compId){var Composition=Edge.Composition,Symbol=Edge.Symbol;
//Edge symbol: 'stage'
(function(symbolName){Symbol.bindElementAction(compId,symbolName,"${_contraption}","click",function(sym,e){sym.getSymbol("contraption").play();});
//Edge binding end
Symbol.bindElementAction(compId,symbolName,"${_logo}","click",function(sym,e){sym.getSymbol("contraption").play();});
//Edge binding end
})("stage");
//Edge symbol end:'stage'

//=========================================================

//Edge symbol: 'contraption'
(function(symbolName){Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",4000,function(sym,e){sym.stop();});
//Edge binding end
Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",3250,function(sym,e){console.log(sym.getSymbol("man-on-trike").play(0));sym.getSymbol("man-on-trike").play(0);});
//Edge binding end
Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",4250,function(sym,e){sym.getSymbol("man-on-trike").play();});
//Edge binding end
Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",5500,function(sym,e){sym.play(0);});
//Edge binding end
})("contraption");
//Edge symbol end:'contraption'

//=========================================================

//Edge symbol: 'man-on-trike'
(function(symbolName){Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",750,function(sym,e){sym.stop();});
//Edge binding end
Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",0,function(sym,e){});
//Edge binding end
})("man-on-trike");
//Edge symbol end:'man-on-trike'

//=========================================================

//Edge symbol: 'scarf-wave'
(function(symbolName){})("scarf-wave");
//Edge symbol end:'scarf-wave'

//=========================================================

//Edge symbol: 'scarfwave'
(function(symbolName){Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",1200,function(sym,e){sym.play(0);});
//Edge binding end
})("scarfwave");
//Edge symbol end:'scarfwave'

//=========================================================

//Edge symbol: 'logo'
(function(symbolName){Symbol.bindTriggerAction(compId,symbolName,"Default Timeline",1000,function(sym,e){sym.stop();});
//Edge binding end
})("logo");
//Edge symbol end:'logo'
})(jQuery,AdobeEdge,"edge-animation");