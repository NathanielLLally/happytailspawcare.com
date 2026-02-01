import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
import { Spinner } from '@wordpress/components';
import image from '../gutenberg-block/image.png';

export default function Edit({ attributes, setAttributes }) {
  const { shortcodeId } = attributes;

  const posts = useSelect((select) =>
    select('core').getEntityRecords('postType', 'sltk', { per_page: -1 })
  , []);

  return (
    <div {...useBlockProps({ className: 'sltk_containerBlock' })}>
      <label className="sltk_selectSlider"><img src={image}></img>Select a Slider: </label>
      {!posts ? (
        <Spinner />
      ) : (
        <>
        <div className='sltk_containerSelect'>
        <select className="sltk_selectValue" value={shortcodeId || ''} onChange={e => setAttributes({ shortcodeId: e.target.value })}>
          <option value="">Select...</option>
          {posts.map(post => (
            <option key={post.id} value={post.id}>
              {post.title.rendered || `Post #${post.id}`}
            </option>     
          ))}
        </select>
        {shortcodeId && (
        <a
          href={`${window.location.origin}/wp-admin/post.php?post=${shortcodeId}&action=edit&from_shortcode=1`}
          target="_blank"
          rel="noopener noreferrer"
          style={{ marginLeft: '10px', display: 'inline-block'}}
        >
          <button class="sltk_editButton" type="button">Edit slider</button>
        </a>
        
      )}
      </div>
        </>
      )}
    </div>
  );
}