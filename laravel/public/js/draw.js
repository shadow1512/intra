var width = 700,
    height = 650,
    radius = Math.min(width, height) / 2;

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.score; });

var arc = d3.svg.arc()
	.outerRadius(radius * 0.5)
	.innerRadius(radius * 0.4);

var outerArc = d3.svg.arc()
	.innerRadius(radius)
	.outerRadius(radius);

var svg = d3.select(".department").append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("class", "department_svg")
    .append("g")

svg.append("g")
	.attr("class", "department_slices");
svg.append("g")
	.attr("class", "department_labels");
svg.append("g")
	.attr("class", "department_lines");

svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

d3.csv('personal_data.csv', function(error, data) {

  data.forEach(function(d) {
    d.color  =  d.color;
    d.score  = +d.score;
    d.label  =  d.label;
    d.url  =  d.url;
    d.icon  =  "../images/person.svg";
  });

  var key = function(d){ return d.data.label; };

  // slice
  var slice = svg.select(".department_slices").selectAll(".department_slice")
      .data(pie(data), key)
    .enter().append("path")
      .attr("fill", function(d) { return d.data.color; })
      .attr("class", "department_slice")
      .attr("d", arc);

  // label
	var label = svg.select(".department_labels").selectAll("text")
		.data(pie(data), key);

  label.enter().append("text")
  .attr("dy", ".35em")
  .attr("x", "1em")
  .text(function(d) {
    return d.data.label;
  })
  .call(wrap, 120)
  .attr("class", "department_label")
  .on("click", function(d,i) {
    window.open(d.data.url);
  });

  function wrap(text, width) {
    text.each(function () {
      var text = d3.select(this),
        words = text.text().split(/\s+/).reverse(),
        word,
        line = [],
        lineNumber = 0,
        lineHeight = 1.5,
        x = text.attr("x"),
        y = text.attr("y"),
        dy = 0,
        tspan = text.text(null)
          .append("tspan")
          .attr("x", x)
          .attr("y", dy + "em");
      while (word = words.pop()) {
        line.push(word);
        tspan.text(line.join(" "));
        if (tspan.node().getComputedTextLength() > width) {
          line.pop();
          tspan.text(line.join(" "));
          line = [word];
          tspan = text.append("tspan")
            .attr("x", x)
            .attr("y", ++lineNumber * lineHeight + dy + "em")
            .text(word);
        }
        if (text.node().getComputedTextLength() < 35) {
          tspan.attr("text-anchor", "end")
        }
      }
    });
  }

  function midAngle(d){
    return d.startAngle + (d.endAngle - d.startAngle)/2;
  }

  label.transition().duration(1000)
    .attrTween("transform", function(d) {
      this._current = this._current || d;
      var interpolate = d3.interpolate(this._current, d);
      this._current = interpolate(0);
      return function(t) {
        var d2 = interpolate(t);
        var outerLabelArc = d3.svg.arc()
        	.innerRadius(radius * 0.87)
        	.outerRadius(radius * 0.87);
        var pos = outerLabelArc.centroid(d2);
        return "translate("+ pos +")";
      };
    })
    .styleTween("text-anchor", function(d){
      this._current = this._current || d;
      var interpolate = d3.interpolate(this._current, d);
      this._current = interpolate(0);
      return function(t) {
        var d2 = interpolate(t);
        return midAngle(d2) < Math.PI ? "middle":"end";
      };
    });

	label.exit()
		.remove();

  // score
  var score = svg.selectAll(".department_score")
    .data(pie(data))
    .enter().append("g")
    .attr("class", "department_score");

  score.append("circle")
    .attr("class", "department_circle")
    .attr("r", "30");

  score.append('clipPath')
  	.append('use')

  score.append('image')
  	.classed('node-icon', true)
  	.attr('xlink:href', d => d.data.icon)
    .attr("x", "0.5em")
    .attr("y", "-0.6em")
    .attr("class", "department_score_ic");

  score.append("text")
    .attr("dy", ".35em")
    .attr("x", "-0.5em")
    .text(function(d) {
      return d.data.score;
    })
    .attr("class", "department_score_tx");

  score.transition().duration(1000)
    .attrTween("transform", function(d) {
      this._current = this._current || d;
      var interpolate = d3.interpolate(this._current, d);
      this._current = interpolate(0);
      return function(t) {
        var d2 = interpolate(t);
        var outerScoreArc = d3.svg.arc()
        	.innerRadius(radius * 0.7)
        	.outerRadius(radius * 0.7);
        var pos = outerScoreArc.centroid(d2);
        return "translate("+ pos +")";
      };
    });

  d3.selection.prototype.moveToFront = function() {
    return this.each(function(){
      this.parentNode.appendChild(this);
    });
  };
  d3.selection.prototype.moveToBack = function() {
    return this.each(function() {
      var firstChild = this.parentNode.firstChild;
      if (firstChild) {
          this.parentNode.insertBefore(this, firstChild);
      }
    });
  };

  score.on("mouseover", function(d) {
    d3.select(this).moveToFront();
  })
  .on("click", function(d) {
    window.open(d.data.url, "_self");
  });

  // lines
	var polyline = svg.select(".department_lines").selectAll("polyline")
		.data(pie(data), key);

	polyline.enter()
		.append("polyline");

	polyline.transition().duration(1000)
		.attrTween("points", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
        var lineArc = d3.svg.arc()
        	.outerRadius(radius * 0.6)
        	.innerRadius(radius * 0.4);
        var outerLineArc = d3.svg.arc()
        	.innerRadius(radius * 0.7)
        	.outerRadius(radius * 0.7);
				var d2 = interpolate(t);
				return [lineArc.centroid(d2), outerLineArc.centroid(d2)];
			};
		})
    .attr("class", "department_line");

	polyline.exit()
		.remove();

  // calculate personal
  var personal =
    data.reduce(function(a, b) {
      return a + b.score;
    }, 0);

  svg.append("svg:text")
    .attr("class", "department_after-score")
    .attr("dy", ".1em")
    .attr("text-anchor", "middle")
    .text(Math.round(personal));

  svg.append("svg:text")
    .attr("class", "department_after-title")
    .attr("dy", ".35em")
    .attr("y", "2em")
    .attr("text-anchor", "middle")
    .text('человек работает в Кодексе');
});
