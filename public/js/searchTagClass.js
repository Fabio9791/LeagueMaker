class Client {
	constructor(button, input, container) {
		this.button = button;
	    this.input = input;
	    this.container = container;
	}

	start() {
		this.button.addEventListener('click', this.load.bind(this));
	}

	async load() {
		this.oReq = new XMLHttpRequest();
		this.oReq.addEventListener("load", this.processResult.bind(this));
		this.oReq.open("GET", "/en/tag/search?pattern=" + this.input.value);
		this.oReq.send();
	}

	async processResult() {
		let data = JSON.parse(this.oReq.response).data;
		this.container.innerHTML = '';
		
		data.forEach(this.processLine.bind(this));
	}
	
	async processLine() {
		this.container.insertAdjacentHTML('beforeend', '<li>'+arguments[0].name+'</li>');
	}
}