import { render, useEffect, useState, useRef } from "@wordpress/element";
import { AddNew } from "./components/AddNew";
import { GalleryItem } from "./components/GalleryItem";

const App = () => {
  const [images, _setImages] = useState(GALLERY_DATA || []);
  const imagesRef = useRef(GALLERY_DATA || []);

  const setImages = (images) => {
    imagesRef.current = images;
    _setImages(images);
  };

  const addFrame = useRef(null);
  const editFrame = useRef(null);
  const currentEditing = useRef(null);

  useEffect(() => {
    addFrame.current = wp.media({
      title: "Select or Upload Images",
      multiple: true,
    });

    addFrame.current.on("select", () => {
      const selection = addFrame.current
        .state()
        .get("selection")
        .map((attachment) => attachment.toJSON());

      const items = selection.map((item) => ({
        id: item.id,
        url: item.sizes.medium.url,
      }));

      setImages([...imagesRef.current, ...items]);
    });

    editFrame.current = wp.media({
      title: "Select or Upload Images",
      multiple: true,
    });

    editFrame.current.on("select", () => {
      const selection = editFrame.current
        .state()
        .get("selection")
        .first()
        .toJSON();

      if (currentEditing.current) {
        const imgs = [...imagesRef.current];
        imgs[currentEditing.current] = {
          id: selection.id,
          url: selection.sizes.medium.url,
        };

        setImages(imgs);
      }

      currentEditing.current = null;
    });
  }, []);

  const galleryItems = images.map((image, index) => (
    <GalleryItem
      {...image}
      key={image.id}
      handleEdit={() => {
        currentEditing.current = index;
        editFrame.current.open();
      }}
      handleDeletion={() => {
        setImages(images.filter((img) => img.id !== image.id));
      }}
    />
  ));

  return (
    <div
      style={{
        display: "flex",
        alignItems: "center",
        flexWrap: "wrap",
      }}
    >
      {galleryItems}
      <AddNew onClick={() => addFrame.current.open()} />
      <input
        type="hidden"
        name="_gallery_data"
        value={JSON.stringify(images)}
      />
    </div>
  );
};

render(<App />, document.getElementById("custom-metabox-gallery"));
