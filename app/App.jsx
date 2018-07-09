import React, {Component} from 'react'
import Pack from './Pack'

class App extends Component {
  constructor (props) {
    super(props)
    this.state = {
      packs: [],
      models: []
    }
  }
  componentDidMount () {
    fetch('data/packs')
      .then(response => {
        if (response.status === 200 || response.status === 206) {
          return response.json()
        }
      })
      .then(body => {
        this.setState(body)
      })
  }
  render () {
    const {packs, models} = this.state
    return (
      <div className="layout">
        <header className="app-header">
          <div className="head-region container">
            <h1 className="brand">Logo</h1>
            <form className="search-form">
              <input type="text" placeholder="search" />
              <button>Search</button>
            </form>
          </div>
        </header>
        <nav className="app-nav">
          <div className="nav-region container">
            <a href="">Members</a>
            <a href="">Join Button</a>
            <a href="">Eligendi, cumque, explicabo!</a>
            <a href="">Dolorum, architecto amet.</a>
            <a href="">Vitae, facere ipsum?</a>
          </div>
        </nav>
        <div className="body-region container">
          <div className="pack-list">
            {packs.map(pack => {
              const modelList = models.filter(model => pack.models.includes(model.id))
              return (
                <Pack key={pack.id} {...pack} models={modelList} />
              )
            })}
          </div>
        </div>
        <footer className="app-footer">
          <p className="container">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus incidunt laborum, repellat nisi, adipisci exercitationem nesciunt cupiditate consequuntur molestias dolore at ratione amet cumque veritatis pariatur in totam. Eum, est.</p>
        </footer>
      </div>
    )
  }
}

export default App
