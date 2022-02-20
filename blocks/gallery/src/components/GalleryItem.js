import { Dashicon } from "@wordpress/components";
import styled from "styled-components";

const GalleryItemStyles = styled.div`
  width: 240px;
  height: 240px;
  border: 2px solid #eee;
  display: flex;
  align-items: center;
  margin: 10px;
  position: relative;

  &:hover {
    .gallery-actions {
      opacity: 1;
    }
  }

  img {
    width: 100%;
    max-height: 100%;
  }

  .gallery-actions {
    position: absolute;
    right: 0;
    top: 0;
    opacity: 0.4;
  }

  .gallery-actions-icon {
    background: #fff;
    cursor: pointer;
    display: block;
    margin: 4px 4px 4px 0;
    width: 38px;
    height: 38px;
    border: 1px solid #eee;

    .dashboard-icons {
      color: #777;
      font-size: 22px;
    }
  }
`;

export const GalleryItem = ({ id, url, handleEdit, handleDeletion }) => {
  return (
    <GalleryItemStyles>
      <div class="gallery-actions">
        <button
          className="gallery-actions-icon gallery-edit"
          type="button"
          title="Edit Item"
          onClick={handleEdit}
        >
          <Dashicon icon="edit" />
        </button>
        <button
          className="gallery-actions-icon gallery-remove"
          type="button"
          title="Remove item"
          onClick={handleDeletion}
        >
          <Dashicon icon="trash" />
        </button>
      </div>
      <img src={url} />
    </GalleryItemStyles>
  );
};
