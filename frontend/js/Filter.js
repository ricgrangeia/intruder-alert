export class Filter
{
	constructor (data = []) {
		this.data = data
	}

	getData(type) {
		const network = document.getElementById('network-filter').value
		const country = document.getElementById('country-filter').value
		const jail = document.getElementById('jail-filter').value
	
		if (type === 'recentBans') {
			var data = this.#getRecentBans()
	
		} else {
			var data = this.data[type].list;
		}
	
		var filtered = data.filter(function (item) {
			if (type === 'ip' || type === 'recentBans') {
				if (network !== 'all' && network != item.network) {
					return false
				}
	
				if (country !== 'all' && country != item.country) {
					return false
				}

				if (jail !== 'all' && jail != item.jail) {
					return false
				}
			}
		
			return true
		})
	
		return filtered;
	}

	setOptions(type) {
		const select = document.getElementById(`${type}-filter`)
		select.innerText = ''
	
		const option = document.createElement('option')
		option.value = 'all'
	
		var text = ''
		var valueName = ''
		switch (type) {
			case 'network':
				text = 	'All networks'
				valueName = 'number'

				break;
			case 'country':
				text = 	'All countries'
				valueName = 'code'
			
				break;
			case 'jail':
				text = 	'All jails'
				valueName = 'name'
			
				break;
		}

		option.innerText = text
		select.appendChild(option)
	
		this.data[type].list.forEach(function (item) {
			const option = document.createElement('option')
			option.value = item[valueName]
			option.innerText = item.name
	
			select.appendChild(option)
		})
	}

	resetOption(name) {
		document.getElementById(`${name}-filter`).value = 'all'
	}

	disableOption(name) {
		document.getElementById(`${name}-filter`).disabled = true;
		document.getElementById(`${name}-filter-reset`).disabled = true;
	}
	
	enableOption(name) {
		document.getElementById(`${name}-filter`).disabled = false;
		document.getElementById(`${name}-filter-reset`).disabled = false;
	}

	#getRecentBans() {
		var events = []

		this.data.ip.list.forEach(ip => {
			ip.events.forEach(event => {
				events.push({
					'address': ip.address,
					'jail': event.jail,
					'network': ip.network,
					'country': ip.country,
					'timestamp': event.timestamp
				})
			})
		})
	
		events.sort(function(a, b){
			var da = new Date(a.timestamp).getTime();
			var db = new Date(b.timestamp).getTime();
			
			return da < db ? -1 : da > db ? 1 : 0
		});
	
		return events.reverse();
	}
}