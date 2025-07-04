$(document).ready(function(){
    
  let canvas;
  let context;
  let proton;
  let renderer;
  let emitter;
  let colorBehaviour;
  //var stats;
  let tha;
  let hcolor = 0;

  main();

  function main() {
    tha = 0;
    index = 0;
    initCanvas();
    //addStats();
    createProton();
    tick();
  }

  function initCanvas(){
    canvas = document.getElementById("canvas");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    context = canvas.getContext("2d");
  }

  /*function addStats() {
    stats = new Stats();
    stats.setMode(2);
    stats.domElement.style.position = "absolute";
    stats.domElement.style.left = "0px";
    stats.domElement.style.top = "0px";
    document.getElementById("container").appendChild(stats.domElement);
  }*/

  function createProton() {
    proton = new Proton();

    emitter = new Proton.Emitter();
    //setRate
    emitter.rate = new Proton.Rate(
      new Proton.Span(2, 8),
      new Proton.Span(0.01)
    );
    //addInitialize
    emitter.addInitialize(new Proton.Position(new Proton.PointZone(0, 0)));
    emitter.addInitialize(new Proton.Mass(1));
    emitter.addInitialize(new Proton.Radius(6, 12));
    emitter.addInitialize(new Proton.Life(2));
    emitter.addInitialize(
      new Proton.V(new Proton.Span(0.3), new Proton.Span(0, 360), "polar")
    );

    //addBehaviour
    emitter.addBehaviour(new Proton.Alpha(1, 0));
    emitter.addBehaviour(new Proton.Scale(0.1, 1.3));
    var color1 = Color.parse(
      "hsl(" + (hcolor % 360) + ", 100%, 50%)"
    ).hexTriplet();
    var color2 = Color.parse(
      "hsl(" + ((hcolor + 50) % 360) + ", 100%, 50%)"
    ).hexTriplet();
    colorBehaviour = new Proton.Color(color1, color2);
    emitter.addBehaviour(colorBehaviour);
    emitter.addBehaviour(
      new Proton.CrossZone(
        new Proton.RectZone(0, 0, canvas.width, canvas.height),
        "collision"
      )
    );

    emitter.p.x = canvas.width / 2;
    emitter.p.y = canvas.height / 2;
    emitter.emit();
    //add emitter
    proton.addEmitter(emitter);

    //canvas renderer
    renderer = new Proton.CanvasRenderer(canvas);
    proton.addRenderer(renderer);

    //debug drawEmitter
    Proton.Debug.drawEmitter(proton, canvas, emitter);
  }

  function tick() {
    requestAnimationFrame(tick);
    //stats.begin();

    //change color
    index++;
    if (index % 10 == 0) {
      hcolor++;
      var color1 = Color.parse(
        "hsl(" + (hcolor % 360) + ", 100%, 50%)"
      ).hexTriplet();
      var color2 = Color.parse(
        "hsl(" + ((hcolor + 50) % 360) + ", 100%, 50%)"
      ).hexTriplet();
      colorBehaviour.reset(color1, color2);
      index = 0;
    }

    tha += Math.PI / 200;
    var p = 400 * Math.sin(2 * tha);
    emitter.p.x = p * Math.cos(tha) + canvas.width / 2;
    emitter.p.y = p * Math.sin(tha) + canvas.height / 2;

    proton.update();
    //stats.end();
  }

});