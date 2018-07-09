import React, {Component, PropTypes} from 'react'
import {render} from 'react-dom'
import './style.scss'

import App from './App'

window.addEventListener('load', e => {
  render(<App />, document.getElementById('app'))
})
