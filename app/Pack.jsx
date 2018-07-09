import React, {PropTypes} from 'react'

const Pack = ({title, desc, models}) => {
  return (
    <article className="pack-item">
      <header className="title">{title}</header>
      <p className="description">{desc}</p>
      <div className="model-list">
        <span className="label">Models: </span>
        {models.map(model => (
          <a key={model.id}>{model.name}</a>
        ))}
      </div>
    </article>
  )
}
Pack.propTypes = {
  title: PropTypes.string,
  desc: PropTypes.string,
  models: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.string,
    name: PropTypes.string
  }))
}

export default Pack
