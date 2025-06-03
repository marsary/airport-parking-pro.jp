class ApiRequest
{
    #csrfToken = null

    constructor() {
        this.#csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }

    post(url, data) {
        return this.fetch(url, 'POST', data)
    }

    put(url, data) {
        return this.fetch(url, 'PUT', data)
    }

    delete(url) {
        return this.fetch(url, 'DELETE')
    }

    get(url, params = {}) {
        const searchParams = new URLSearchParams()
        Object.keys(params).map((key) => {
            searchParams.set(key, params[key])
        })

        url = url + '?' + searchParams.toString();
        console.log(url);

        return this.fetch(url, 'GET')
    }

    async fetch(url = '', method, data = {}) {
        console.log(url);
        // 既定のオプションには * が付いています
        const option = {
            method: method, // *GET, POST, PUT, DELETE, etc.
            headers: {
              'Content-Type': 'application/json',
              'Accept' : 'application/json',
              'X-CSRF-TOKEN': this.#csrfToken
            },
        }
        if(method === 'POST' || method === 'PUT') {
            option['body'] = JSON.stringify(data) // 本文のデータ型は "Content-Type" ヘッダーと一致させる必要があります
        }

        const response = await fetch(url, option)

        if (!response.ok) { //通信失敗
            throw new Error(`${response.status}: ${response.statusText}`);
        }

        return response.json(); // JSON のレスポンスをネイティブの JavaScript オブジェクトに解釈
      }

}

window.apiRequest = new ApiRequest()

