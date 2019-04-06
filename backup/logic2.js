var CELL_STATE = {
	NORMAL: 0,
	SCANED: 1,
	PREERASE: 2,
	ERASED: 3
}

var TYPE_NUM = 5;
var SCORE_PRE_CELL = 100;

// ----------------------------Cell-----------------------------------
class Cell{

	constructor(type) {
		this.type = type;
	 	this.state = CELL_STATE.NORMAL;
	 	this.x = 0;
	 	this.y = 0;
	 	this.matchx = 0;
	 	this.matchy = 0;
	}


	getType() {
		return this.type;
	}

	getState() {
		return this.state;
	}

	setPos(x, y) {
		this.x = parseInt(x);
		this.y = parseInt(y);
	}

	getPosX() {
		return this.x;
	}

	getPosY() {
		return this.y;
	}

	isScaned() {
		return this.state == CELL_STATE.SCANED;
	}

	isReadyErase() {
		return this.state == CELL_STATE.PREERASE;
	}

	isErased() {
		return this.state == CELL_STATE.ERASED;
	}

	updateState(state) {
		this.state = state;
	}

	setMatchX(matchx) {
		this.matchx = parseInt(matchx);
	}

	getMatchX() {
		return this.matchx;
	}

	setMatchY(matchy) {
		this.matchy = parseInt(matchy);
	}

	getMatchY() {
		return this.matchy;
	}

	resetState() {
		this.state = CELL_STATE.NORMAL;
		this.matchx = 0;
		this.matchy = 0;
	}

	randomResetType() {
		this.type = Math.floor(Math.random()* TYPE_NUM);
	}

	erase() {
		this.randomResetType();
		this.resetState();
	}
}


//------------------------------Grid-------------------------------

function Grid (cols, rows) {
    this.cols = cols;
    this.rows = rows;
    this.board = new Array();

    this.lastClickPosX = -1;
 	this.lastClickPosY = -1;

 	this.score = 0;
}

Grid.prototype.initLogic = function() {
	for (var i = 0; i < this.rows; i++) {
		this.board[i] = new Array();
		for (var j = 0; j < this.cols; j++) {
			var cell = new Cell(Math.floor(Math.random()* TYPE_NUM));
			cell.setPos(j, i);
			this.board[i].push(cell);
			//console.log("initLogic", i, j, this.board[i][j]);
		}
		//console.log(this.board);
	}

	//console.log(this.board[0].length);
}

Grid.prototype.isVaildPos = function(x, y) {
	return (x >= 0 && x < this.cols) && (y >= 0 && y < this.rows);
};

Grid.prototype.isAdjacent = function(x1, y1, x2, y2) {
	if (!this.isVaildPos(x1, y1) || !this.isVaildPos(x2, y2))
		return false;

	if (x1 != x2 && y1 != y2)
		return false;

	if ((x1 == x2) && (Math.abs(y1 - y2) == 1))
		return true;

	if ((y1 == y2) && (Math.abs(x1 - x2) == 1))
		return true;

	return false;
}

Grid.prototype.move = function(x1, y1, x2, y2) {
	if (!this.isAdjacent(x1, y1, x2, y2))
		return false;

	this.swapCell(x1, y1, x2, y2);
	var startCell = this.getCellByPos(x2, y2);
	console.assert(startCell != null);
	startCell.updateState(CELL_STATE.SCANED);

	//scan pos "to"
	this.scanPos1(x2, y2, x2, y2);
	this.printState(CELL_STATE.SCANED);
	this.scanPos2();
	this.scanPos3();
	this.printState(CELL_STATE.PREERASE);

	//scan pos "from"
	this.scanPos1(x1, y1, x1, y1);
	this.scanPos2();
	this.scanPos3();

	//do erase
	this.doErase();
};

Grid.prototype.getCellByPos = function(x, y) {
	if (!this.isVaildPos(x, y))
		return null;

	return this.board[y][x];
}

Grid.prototype.setCellByPos = function(newcell, x, y) {
	if (!Cell.prototype.isPrototypeOf(newcell))
		return null;

	if (!this.isVaildPos(x, y))
		return null;

	this.board[y][x] = newcell;
	newcell.setPos(x, y);
}

Grid.prototype.swapCell = function(x1, y1, x2, y2) {
	if (!this.isAdjacent(x1, y1, x2, y2))
		return null;

	var cell_1 = this.getCellByPos(x1, y1);
	var cell_2 = this.getCellByPos(x2, y2);
	this.setCellByPos(cell_1, x2, y2);
	this.setCellByPos(cell_2, x1, y1);
};

Grid.prototype.scanPos1 = function(scanPosX, scanPosY, parentPosX, parentPosY) {
	scanPosX = parseInt(scanPosX);
	scanPosY = parseInt(scanPosY);
	parentPosX = parseInt(parentPosX);
	parentPosY = parseInt(parentPosY);

	//console.log("scanPos1: %d %d %d %d", scanPosX, scanPosY, parentPosX, parentPosY);
	var parentCell = this.getCellByPos(parentPosX, parentPosY);
	if (parentCell == null) {
		console.log("parentCell is null");
		return;
	}

	//left
	var leftPosX = scanPosX - 1;
	var leftPosY = scanPosY;
	//console.log("(%d %d) (%d %d)", leftPosX, leftPosY, parentPosX, parentPosY);
	var leftCell = this.getCellByPos(leftPosX, leftPosY);
	//console.log("(%d %d) (%d %d)", leftPosX, leftPosY, parentPosX, parentPosY);
	//console.log("leftCell:", leftCell);
	if (leftCell != null
		&& (leftPosX != parentPosX || leftPosY != parentPosY)
		&& leftCell.getState() == CELL_STATE.NORMAL
		&& leftCell.getType() == parentCell.getType()
		)
	{
		leftCell.updateState(CELL_STATE.SCANED);
		this.scanPos1(leftPosX, leftPosY, scanPosX, scanPosY);
	}
	//console.log("-----scan pos: scanPosX=%d, scanPosY=%d", scanPosX, scanPosY);

	//top
	var topPosX = scanPosX;
	var topPosY = scanPosY - 1;
	//console.log("(%d %d) (%d %d)", topPosX, topPosY, parentPosX, parentPosY);
	var topCell = this.getCellByPos(topPosX, topPosY);
	//console.log("(%d %d) (%d %d)", topPosX, topPosY, parentPosX, parentPosY);
	//console.log("topCell:", topCell);
	if (topCell != null
		&& (topPosX != parentPosX || topPosY != parentPosY)
		&& topCell.getState() == CELL_STATE.NORMAL
		&& topCell.getType() == parentCell.getType()
		)
	{
		topCell.updateState(CELL_STATE.SCANED);
		this.scanPos1(topPosX, topPosY, scanPosX, scanPosY);
	}
	//console.log("-----scan pos: scanPosX=%d, scanPosY=%d", scanPosX, scanPosY);

	//right
	var rightPosX = scanPosX + 1;
	var rightPosY = scanPosY;
	//console.log("(%d %d) (%d %d)", rightPosX, rightPosY, parentPosX, parentPosY);
	var rightCell = this.getCellByPos(rightPosX, rightPosY);
	//console.log("(%d %d) (%d %d)", rightPosX, rightPosY, parentPosX, parentPosY);
	//console.log("rightCell:", rightCell);
	if (rightCell != null
		&& (rightPosX != parentPosX || rightPosY != parentPosY)
		&& rightCell.getState() == CELL_STATE.NORMAL
		&& rightCell.getType() == parentCell.getType()
		)
	{
		rightCell.updateState(CELL_STATE.SCANED);
		this.scanPos1(rightPosX, rightPosY, scanPosX, scanPosY);
	}
	//console.log("-----scan pos: scanPosX=%d, scanPosY=%d", scanPosX, scanPosY);

	//down
	var downPosX = scanPosX;
	var downPosY = scanPosY + 1;
	//console.log("(%d %d) (%d %d)", downPosX, downPosY, parentPosX, parentPosY);
	var downCell = this.getCellByPos(downPosX, downPosY);
	//console.log("(%d %d) (%d %d)", downPosX, downPosY, parentPosX, parentPosY);
	//console.log("downCell:", downCell);
	if (downCell != null
		&& (downPosX != parentPosX || downPosY != parentPosY)
		&& downCell.getState() == CELL_STATE.NORMAL
		&& downCell.getType() == parentCell.getType()
		)
	{
		downCell.updateState(CELL_STATE.SCANED);
		this.scanPos1(downPosX, downPosY, scanPosX, scanPosY);
	}
	//console.log("-----scan pos: scanPosX=%d, scanPosY=%d", scanPosX, scanPosY);
};

function matchThreeX(xList) {
	for (var itor = 0; itor < xList.length; itor++) {
		var vecCell = xList[itor];
		if (vecCell == null)
			continue;

		if (vecCell.length < 3)
			continue;

		var start = 0;
		var matchCount = 1;
		var i;
		for (i = 1; i < vecCell.length; i++) {
			var prevCell = vecCell[i-1];
			var currCell = vecCell[i];
			//console.assert(prevCell && currCell, "logic error");
			if ( prevCell == null || currCell == null
				|| prevCell.getPosX() != currCell.getPosX()
				)
			{
				console.assert(0, "logic error");
				return;
			}

			if (currCell.getPosY() == prevCell.getPosY() + 1) {
				matchCount++;//continuous
			}
			else if(currCell.getPosY() > prevCell.getPosY()+ 1) {
				//interrupt
				for (var j = start; j < i; j++) {
					console.assert(vecCell[j].getMatchX() == 0);
					vecCell[j].setMatchX(matchCount);
				}
				start = i;
				matchCount = 1;
			}
			else {
				console.log("x-wtf?!: ", prevCell, currCell);
				console.assert(0, "vec's data is not sorted!");
			}
		}

		//loop end, update cell's ref
		for (var j = start; j < i; j++) {
			console.assert(vecCell[j].getMatchX() == 0);
			vecCell[j].setMatchX(matchCount);
		}
	}
}

function matchThreeY(yList) {
	for (var itor = 0; itor < yList.length; itor++) {
		var vecCell = yList[itor];
		if (vecCell == null)
			continue;
		if (vecCell.length < 3)
			continue;

		var start = 0;
		var matchCount = 1;
		var i;
		for (i = 1; i < vecCell.length; i++) {
			var prevCell = vecCell[i-1];
			var currCell = vecCell[i];
			//console.assert(prevCell && currCell, "logic error");
			if ( prevCell == null || currCell == null
				|| prevCell.getPosY() != currCell.getPosY()
				)
			{
				console.assert(0, "logic error");
				return;
			}

			if (currCell.getPosX() == prevCell.getPosX() + 1) {
				matchCount++;//continuous
			}
			else if(currCell.getPosX() > prevCell.getPosX() + 1) {
				//interrupt
				for (var j = start; j < i; j++) {
					console.assert(vecCell[j].getMatchY() == 0);
					vecCell[j].setMatchY(matchCount);
				}
				start = i;
				matchCount = 1;
			}
			else {
				console.log("y-wtf?!: ", prevCell, currCell);
				console.assert(0, "vec's data is not sorted!");
			}
		}

		//loop end, update cell's ref
		for (var j = start; j < i; j++) {
			console.assert(vecCell[j].getMatchY() == 0);
			vecCell[j].setMatchY(matchCount);
		}
	}
}

Grid.prototype.scanPos2 = function() {
	var xList = new Array();//x same, y not same
	var yList = new Array();//y same, x not same

	//collect pos to xList and yList
	for (var i = 0; i < this.board.length; i++) {
		for (var j = 0; j < this.board[i].length; ++j) {
			var cell = this.board[i][j];
			if (cell == null || !Cell.prototype.isPrototypeOf(cell)) {
				console.assert(0, "should not happed");
				continue;
			}

			if (cell.getState() != CELL_STATE.SCANED)
				continue;

			var posX = cell.getPosX();
			var posY = cell.getPosY();
			console.assert(posX == j && posY == i);

			//x
			if (xList[posX] == null)
				xList[posX] = new Array();
			xList[posX].push(cell);
			//y
			if (yList[posY] == null)
				yList[posY] = new Array();
			yList[posY].push(cell);
		}
	}

	//scan match three
	matchThreeX(xList);
	matchThreeY(yList);
};

Grid.prototype.scanPos3 = function() {
	for (var i = 0; i < this.board.length; i++) {
		for (var j = 0; j < this.board[i].length; ++j) {
			var cell = this.board[i][j];
			if (cell == null || !Cell.prototype.isPrototypeOf(cell)) {
				console.assert(0, "should not happed");
				continue;
			}

			if (cell.getMatchX() >= 3 || cell.getMatchY() >= 3) {
				cell.updateState(CELL_STATE.PREERASE);
			}
			else {
				cell.resetState();
			}

		}
	}
};

Grid.prototype.doErase = function() {
	for (var i = 0; i < this.board.length; i++) {
		for (var j = 0; j < this.board[i].length; ++j) {
			var cell = this.board[i][j];
			if (cell == null || !Cell.prototype.isPrototypeOf(cell)) {
				console.assert(0, "should not happed");
				continue;
			}

			if (cell.getState() == CELL_STATE.PREERASE) {
				this.score = this.score + SCORE_PRE_CELL;
				cell.erase();
			}
		}
	}
};

Grid.prototype.printNormal = function() {
	var array = new Array();
	for (var i = 0; i < this.board.length; i++) {
		array[i] = new Array();
		for (var j = 0; j < this.board[i].length; ++j) {
			array[i][j] = this.board[i][j].getType();
		}
	}
	console.log(array);
};

Grid.prototype.printPos = function() {
	var array = new Array();
	for (var i = 0; i < this.board.length; i++) {
		array[i] = "";
		for (var j = 0; j < this.board[i].length; ++j) {
			array[i] = array[i] + this.board[i][j].getType() + "(" + j + i + ") ";
		}
	}
	console.log(array);
};

Grid.prototype.printState = function(state) {
	var array = new Array();
	for (var i = 0; i < this.board.length; i++) {
		array[i] = "";
		for (var j = 0; j < this.board[i].length; ++j) {
			var cell = this.board[i][j];
			//console.log(cell.getState());
			if (cell.getState() == state)
				array[i] = array[i] + "* ";
			else
				array[i] = array[i] + this.board[i][j].getType() + " ";
		}
		console.log(array[i]);
	}
	console.log("-----------------")
	//console.log(array);
};

Grid.prototype.onCellClick = function(pos_x, pos_y) {
	console.assert(pos_x != -1 && pos_y != -1);

	//first click
	console.log(this.lastClickPosX, this.lastClickPosY, pos_x, pos_y);
	if (this.lastClickPosX == -1 || this.lastClickPosY == -1) {
		this.lastClickPosX = pos_x;
		this.lastClickPosY = pos_y;
		return;
	}

	//second click, move it
	this.move(this.lastClickPosX, this.lastClickPosY, pos_x, pos_y);

	//clear lastClick memory
	this.lastClickPosX = -1;
	this.lastClickPosY = -1;

	//update view: pic
	this.imageView();

	//update view: score
	//console.log(document.getElementById('score').text);
	document.getElementById('score').innerHTML = this.score;
};

var g = null;

function newGame() {
	console.log("call newgame");
	time_fun();
	setInterval("refer()",1000); 
	if (g != null) {
		alert("The game has already begun! Don't repeat click the START button! \n\nif you want start a new game, please refresh this page :)");
		return;
	}
	g = new Grid(5, 5);
	g.initLogic();
	g.printState(1);
	g.initView();

}

function oncellclick(div) {
	var divid = div.id.split("-");
	var pos_y = parseInt(divid[2]);
	var pos_x = parseInt(divid[3]);
	console.log("oncellclick", pos_x, pos_y);

	if (g == null)
		console.assert(0);
	g.onCellClick(pos_x, pos_y);
}


Grid.prototype.initView = function() {
    $("#grid-container").height(getPosLeft(this.cols, this.rows) - 40);
    $("#grid-container").width(getPosTop(this.cols, this.rows) - 40);
    console.log("call initView2", this.board);
    for (var i = 0; i < this.board.length; i++) {
        for (var j = 0; j < this.board[i].length; j++) {

            var gridCell = $('<div class="grid-cell" onclick="oncellclick(this)" id="grid-cell-' + i + '-' + j + '">').appendTo("#grid-container");

            //Use getPosTop() to set the distance between every grid and top
            gridCell.css("top", getPosTop(i, j));
            //Use getPosTop() to set the distance between every grid and left
            gridCell.css("left", getPosLeft(i, j));
        }
    }

    this.updateBoardView();
};

Grid.prototype.updateBoardView = function() {
    for(var i=0;i<this.board.length;i++){
        for(var j=0;j<this.board[i].length;j++) {
            $('#grid-cell-' + i + '-' + j).append('<img id=cellimg-' +i + '-' + j +' src="img/' + this.board[i][j].getType() + '.png">');
        }
    }
};

Grid.prototype.imageView = function() {
    for(var i=0;i<this.board.length;i++){
        for(var j=0;j<this.board[i].length;j++) {
            document.getElementById('cellimg-' + i + '-' + j).src = "img/" + this.board[i][j].getType() + '.png';
        }
    }
};

function getPosTop(i, j) {
  return 20 + i * 120;
}

function getPosLeft(i, j) {
	return 20 + j * 120;
}

/////////////////////////////////////////////////////////////////
// node.js 's test code
/*
var grid = new Grid(5, 5);
grid.init();
grid.printPos();
grid.printNormal();
//grid.printState(CELL_STATE.NORMAL);

////////////////////////////////////////////
// IO
var readline = require('readline');

var rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});


rl.on('line', function(line){
	//console.log(line);
	var params = line.split(" ");
	var from_x = params[0];
	var from_y = params[1];
	var to_x = params[2];
	var to_y = params[3];
	console.log("from_x=%d, from_y=%d, to_x=%d, to_y=%d", from_x, from_y, to_x, to_y);
	grid.move(from_x, from_y, to_x, to_y);

	console.log("after move:");
	grid.printPos();
	grid.printNormal();
});
rl.on('close', function() {
    console.log('quit');
    process.exit(0);
});
*/

//-------------------------------------------------------------------------------------------------------------------
// client functions
//$(function () {
//    newGame();
//});
