<?PHP
	require_once './functions.php';
	require_once './jpgraph/jpgraph.php';
	require_once './jpgraph/jpgraph_date.php';
	require_once './jpgraph/jpgraph_bar.php';
	
	$Device = $_GET["device"];
	$Unix_Start_Date = $_GET["s_time"];
	$Unix_Stop_Date = $_GET["e_time"];
	$time = array();
	$power = array();
	
	//now add 2 dummy entries so that we always display a graph with the correct range, and get no errors for empty graphs
	$power[]='0';
	$time[]=$Unix_Start_Date;
	$power[]='0';
	$time[]=$Unix_Stop_Date;
	
	time_bounded_powerstats_query($time, $power, $Device, $Unix_Start_Date, $Unix_Stop_Date);
	build_graph($power,$time,'Power Readings from <'.date('d l Y @ H:i:s',$Unix_Start_Date).'> to <'.date('d l Y @ H:i:s',$Unix_Stop_Date).">",'Power','Time');
	
	function build_graph($ydata,$xdata,$title,$y_axis,$x_axis){
		//initialize graph
		$width = 600; $height = 400;
		$graph = new Graph($width,$height);
		$graph->SetScale('datint');//xaxis=date yaxis=int
		
		//enter titles and labels
		$graph->title->Set($title);
		$graph->xaxis->title->Set($x_axis);
		$graph->yaxis->title->Set($y_axis);
		
		//position titles and labels
		$graph->SetMargin(40,40,30,150);
		$graph->xaxis->SetTitleMargin(-10);
		$graph->yaxis->SetTitleMargin(2);
		$graph->yaxis->SetLabelMargin(15);
		$graph->xaxis->SetLabelMargin(10);
		$graph->xaxis->SetLabelAngle(90);
		
		//make a barplot to put in the graph
		$barplot=new BarPlot($ydata, $xdata);
		$graph->Add($barplot);
		
		//draw the graph
		$graph->Stroke();
		}
	?>