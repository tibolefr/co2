<!DOCTYPE html>
<meta charset="utf-8">
<style>

.links line {
  stroke: #999;
  stroke-opacity: 0.6;
}

.nodes circle {
  stroke: black	;
  stroke-width: 0px;
}

.graph{
    float: right;
}
#tags{
    background-color: #eee;
    width:200px;
    float: left;
    height:600px;
}
#tags a{
    color: #333;
    text-decoration: none;
}
</style>
<div  id="tags" ></div>
<svg id="graph" width="900" height="600"></svg>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script>

//create somewhere to put the force directed graph
var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
    
var radius = 15; 

console.log(<?php echo json_encode($data); ?>);
var tags = <?php echo json_encode($tags); ?>;
var nodes_data = <?php echo json_encode($data); ?>;
var links_data = <?php echo json_encode($links); ?>;

tags.forEach(function (t) {
    if (t != "") 
        document.getElementById("tags").innerHTML = document.getElementById("tags").innerHTML + "<a href='/ph/co2/graph/search/tag/"+t+"'>#"+t+"</a><br/>";
  })

//set up the simulation and add forces  
var simulation = d3.forceSimulation()
					.nodes(nodes_data);
                              
var link_force =  d3.forceLink(links_data)
                        .id( function(d) { return d.id; } )
                        .strength( function (d) { return d.strength; } );             
         
var charge_force = d3.forceManyBody()
    .strength(-120); 
    
var center_force = d3.forceCenter(width / 2, height / 2);  
                      
simulation
    .force("charge_force", charge_force)
    .force("center_force", center_force)
    .force("links",link_force)
 ;

//add tick instructions: 
simulation.on("tick", tickActions );

//add encompassing group for the zoom 
var g = svg.append("g")
    .attr("class", "everything");

//draw lines for the links 
var link = g.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(links_data)
    .enter().append("line")
      .attr("stroke-width", 1)
      .style("stroke", linkColour);        

//draw circles for the nodes 
var node = g.append("g")
        .attr("class", "nodes") 
        .selectAll("circle")
        .data(nodes_data)
        .enter()
        .append("circle")
        .attr("r", radius)
        .attr("fill", circleColour)
        .on('click', selectNode);
/*
node.append("image")
      .attr("xlink:href", "https://github.com/favicon.ico")
      .attr("x", -8)
      .attr("y", -8)
      .attr("width", 16)
      .attr("height", 16);
*/
var text = g.append("g")
        .attr("class", "texts")
        .selectAll("text")
        .data(nodes_data)
        .enter()
        .append('text')
        .text(function (node) { return node.label })
        .attr('font-size', 25)
        .attr('dx', 15)
        .attr('dy', 4)


//add drag capabilities  
var drag_handler = d3.drag()
	.on("start", drag_start)
	.on("drag", drag_drag)
	.on("end", drag_end);	
	
drag_handler(node);


//add zoom capabilities 
var zoom_handler = d3.zoom()
    .on("zoom", zoom_actions);

zoom_handler(svg);     

/** Functions **/

//Function to choose what color circle we have
//Let's return blue for males and red for females
function circleColour(d){
	if(d.level ==0){
        return "purple";
    }else if(d.level ==1){
		return "red";
	} else {
		return "#cccccc";
	}
}

//Function to choose the line colour and thickness 
//If the link type is "A" return green 
//If the link type is "E" return red 
function linkColour(d){
	if(d.type == "A"){
		return "green";
	} else {
		return "#333333";
	}
}

//Drag functions 
//d is the node 
function drag_start(d) {
 if (!d3.event.active) simulation.alphaTarget(0.3).restart();
    d.fx = d.x;
    d.fy = d.y;
}

//make sure you can't drag the circle outside the box
function drag_drag(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

function drag_end(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}

//Zoom functions 
function zoom_actions(){
    g.attr("transform", d3.event.transform)
}

function tickActions() {
    //update circle positions each tick of the simulation 
       node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
        text
      .attr('x', function (d) { return d.x })
      .attr('y', function (d) { return d.y })
    //update link positions 
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });
} 

function selectNode(selectedNode) {
  console.log(selectedNode);
  types = ["citoyen", "organization", "project", "event"];
  if(selectedNode.id.length > 20 )
    window.location.href = "/ph/co2/graph/d3/id/"+selectedNode.id+"/type/"+types[selectedNode.group-1];
  }

</script>