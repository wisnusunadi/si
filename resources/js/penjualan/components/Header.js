import React from "react";

const Breadcrumb = ({ title = "Lab", breadcumbs = [] }) => {
    const lastBreadcumb = breadcumbs[breadcumbs.length - 1];

    return (
        <div className="container-fluid">
            <div className="row mb-2">
                <div className="col-sm-6">
                    <h4>{title}</h4>
                </div>
                <div className="col-sm-6">
                    <ol className="breadcrumb float-sm-right">
                        {breadcumbs.map((breadcumb, index) => (
                            <li
                                key={index}
                                className={`breadcrumb-item ${
                                    index === breadcumbs.length - 1
                                        ? "active"
                                        : ""
                                }`}
                            >
                                {lastBreadcumb !== breadcumb ? (
                                    <a href={breadcumb.link}>
                                        {breadcumb.name}
                                    </a>
                                ) : (
                                    <span>{breadcumb.name}</span>
                                )}
                            </li>
                        ))}
                    </ol>
                </div>
            </div>
        </div>
    );
};

export default Breadcrumb;
